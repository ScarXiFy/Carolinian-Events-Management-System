<?php

function event_trim_value($value)
{
    return trim((string) $value);
}

function event_build_form_data($post)
{
    $rawLocation = isset($post['location_select']) ? event_trim_value($post['location_select']) : '';
    $otherLocation = isset($post['location_other']) ? event_trim_value($post['location_other']) : '';

    if ($rawLocation === 'Other' && $otherLocation !== '') {
        $location = $otherLocation;
    } elseif ($rawLocation !== '') {
        $location = $rawLocation;
    } else {
        $location = '';
    }

    $category = isset($post['category']) ? event_trim_value($post['category']) : 'Other';
    if ($category === '') {
        $category = 'Other';
    }

    return [
        'event_name' => isset($post['event_name']) ? event_trim_value($post['event_name']) : '',
        'organizer' => isset($post['organizer']) ? event_trim_value($post['organizer']) : '',
        'description' => isset($post['description']) ? event_trim_value($post['description']) : '',
        'event_date' => isset($post['event_date']) ? event_trim_value($post['event_date']) : '',
        'event_time' => isset($post['event_time']) ? event_trim_value($post['event_time']) : '',
        'location' => $location,
        'category' => $category,
    ];
}

function event_validate_form_data($event)
{
    if (
        $event['event_name'] === '' ||
        $event['organizer'] === '' ||
        $event['event_date'] === '' ||
        $event['event_time'] === '' ||
        $event['location'] === ''
    ) {
        return 'Please fill in all required fields.';
    }

    if (strlen($event['event_name']) < 3) {
        return 'Event Name must be at least 3 characters.';
    }

    if (strlen($event['organizer']) < 2) {
        return 'Organizer must be at least 2 characters.';
    }

    $date = DateTime::createFromFormat('Y-m-d', $event['event_date']);
    if (!$date || $date->format('Y-m-d') !== $event['event_date']) {
        return 'Please enter a valid event date.';
    }

    $time = DateTime::createFromFormat('H:i', $event['event_time']);
    $timeWithSeconds = DateTime::createFromFormat('H:i:s', $event['event_time']);
    if (
        (!$time || $time->format('H:i') !== $event['event_time']) &&
        (!$timeWithSeconds || $timeWithSeconds->format('H:i:s') !== $event['event_time'])
    ) {
        return 'Please enter a valid event time.';
    }

    return '';
}

function event_compute_status($eventDate, $eventTime, $now = null)
{
    $now = $now ?: new DateTime();
    $eventDateTime = new DateTime($eventDate . ' ' . $eventTime);
    $eventDateOnly = (new DateTime($eventDate))->format('Y-m-d');
    $todayOnly = $now->format('Y-m-d');

    if ($eventDateOnly < $todayOnly) {
        return 'Completed';
    }

    if ($eventDateOnly === $todayOnly) {
        return $eventDateTime <= $now ? 'Completed' : 'Ongoing';
    }

    return 'Upcoming';
}

function event_duplicate_exists($conn, $event, $excludeId = null)
{
    $sql = "SELECT id FROM events
            WHERE LOWER(TRIM(event_name)) = LOWER(TRIM(?))
              AND event_date = ?
              AND LOWER(TRIM(location)) = LOWER(TRIM(?))";

    if ($excludeId !== null) {
        $sql .= " AND id <> ?";
    }

    $sql .= " LIMIT 1";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException('Could not prepare duplicate check: ' . $conn->error);
    }

    if ($excludeId !== null) {
        $excludeId = (int) $excludeId;
        $stmt->bind_param('sssi', $event['event_name'], $event['event_date'], $event['location'], $excludeId);
    } else {
        $stmt->bind_param('sss', $event['event_name'], $event['event_date'], $event['location']);
    }

    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();

    return $exists;
}

function event_duplicate_message()
{
    return 'This event already exists on the same date and location.';
}
