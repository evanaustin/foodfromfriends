<?php
/**
 * For quickly generating MySQL datetime strings.
 */
class Time {
    function __construct() {
        date_default_timezone_set('America/New_York');
    }

    /**
     * Generates a `datetime` or `date` string for the current time.
     *
     * @param bool $date_only Pass in `true` to return the date without time information
     * @return string
     */
    public static function now($date_only = false) {
        return date(($date_only === false ? 'Y-m-d H:i:s' : 'Y-m-d'));
    }

    /**
     * Generates a `datetime` or `date` string for a given timestamp or date string.
     *
     * @param int|string $time Timestamp or date string to be passed into `strtotime()`.
     * @param bool $date_only Pass in `true` to return the date without time information
     * @return string
     * @throws \Exception if `$time` was invalid
     */
    public static function create($time, $date_only = false) {
        if (!isset($time)) {
            $time = time();
        } else if (\Num::is_int($time) === false) {
            $time = strtotime($time);

            if ($time === false) {
                throw new \Exception('Invalid time string.');
            }
        }

        return date(($date_only === false ? 'Y-m-d H:i:s' : 'Y-m-d'), $time);
    }

    /**
     * Given a timestamp or date string, lists all days in that week Monday through Sunday.
     *
     * @param int|string $time Timestamp or date string to be passed into `strtotime()`.
     * @param bool|null  $week_starts_monday Will grab all days starting with Monday if `true`, with Sunday otherwise
     * @return array Array of `YYYY-MM-DD` strings Monday through Sunday
     * @throws \Exception
     */
    public static function dates_in_week($time = null, $week_starts_monday = false) {
        if (!isset($time)) {
            $time = time();
        } else if (\Num::is_int($time) === false) {
            $time = strtotime($time);

            if ($time === false) {
                throw new \Exception('Invalid time string.');
            }
        }

        // Calculate the number of days since Monday/Sunday
        $dow = date('w', $time);
        $offset = ($week_starts_monday ? $dow - 1 : $dow);
        if ($offset < 0) {
            $offset = 6;
        }

        // Calculate timestamp for the Monday/Sunday
        $ts = $time - $offset * 86400;

        // Loop through the week
        $week = [];
        for ($i = 0; $i < 7; $i++, $ts += 86400) {
            $week [] = self::create($ts, true);
        }

        return $week;
    }

    /**
     * Gives the closest Monday before the given date.
     *
     * @param $date Date in YYYY-MM-DD format
     * @return string YYYY-MM-DD
     */
    public static function previous_monday($date) {
        $date = new \DateTime($date);

        return $date->modify('last monday')->format('Y-m-d');
    }

    /**
     * Gives the closest Monday after the given date.
     *
     * @param $date Date in YYYY-MM-DD format
     * @return string YYYY-MM-DD
     */
    public static function next_monday($date) {
        $date = new \DateTime($date);

        return $date->modify('next monday')->format('Y-m-d');
    }

    /**
     * Gives the closest Sunday before the given date.  Used in week-by-week navigation on the weekly tasks page.
     *
     * @param $date Date in YYYY-MM-DD format
     * @return string YYYY-MM-DD
     */
    public static function previous_sunday($date) {
        $date = new \DateTime($date);

        return $date->modify('last sunday')->format('Y-m-d');
    }

    /**
     * Gives the closest Sunday after the given date.  Used in week-by-week navigation on the weekly tasks page.
     *
     * @param $date Date in YYYY-MM-DD format
     * @return string YYYY-MM-DD
     */
    public static function next_sunday($date) {
        $date = new \DateTime($date);

        return $date->modify('next sunday')->format('Y-m-d');
    }

    function elapsed($datetime, $full = false) {
        $now = new \DateTime($this->now());
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];
        
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        
        return [
            'full' => $string ? implode(', ', $string) . ' ago' : 'just now',
            'diff' => $diff
        ];
    }
    
    function until($datetime, $deadline) {
        $now    = new \DateTime($this->now());
        $then   = new \DateTime($datetime);
        $until  = date_add($then, date_interval_create_from_date_string($deadline));
        $diff   = $now->diff($until);
    
        $d = $diff->format('%a');
        $h = $diff->format('%h');
        $m = $diff->format('%i');
        $s = $diff->format('%s');

        if ($d > 0) {
            $full = $d . ' day' . (($d != 1) ? 's ' : ' ' . $h . ' hour' . (($h != 1) ? 's' : ''));
        } else if ($d == 0 && $h < 24 && $h > 0) {
            $full = $h . ' hour' . (($h != 1) ? 's ': ' ') . $m . ' minute' . (($m != 1) ? 's' : '');
        } else if ($h == 0 && $m > 0) {
            $full = $m . ' minute' . (($m != 1) ? 's ' : ' ' . $s . ' second' . (($s != 1) ? 's' : ''));
        } else if ($h == 0 && $m == 0 && $s > 0) {
            $full = $s . ' second' . (($s != 1) ? 's' : '');
        }
        
        return [
            'full' => $full,
            'diff' => $diff
        ];
    }
}