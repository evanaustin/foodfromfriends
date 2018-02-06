<?php
/**
 * Custom methods for dealing with numbers.
 */
class Num {
    /**
     * Checks to see if a number can be a valid ID within the database, that is:
     *
     * * An integer
     * * Greater than or equal to zero (we often allow zero as a special case)
     * * Less than 4,294,967,296 (will have to change this if we move to `bigint`)
     *
     * Can be fed a string or an int (or anything else) and will be validated appropriately.
     *
     * @param mixed $val Value to check
     * @return bool
     */
    public static function is_id($val) {
        if (self::clean_id($val) === false || $val > 4294967295) {
            return false;
        }

        return true;
    }

    /**
     * Returns a filtered ID value, e.g. `1e9` becomes `1000000000`.  If the value can't be mapped to an integer
     * above 0, `false` is returned.  Best used after calling `is_id()`.
     *
     * @param $val Value to filter
     * @return mixed Filtered integer if valid, `false` if not.
     */
    public static function clean_id($val) {
        return filter_var($val, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0]
        ]);
    }

    /**
     * Checks to see if a given input is an integer, even if fed a string.
     *
     * @param mixed $val Value to check
     * @return bool
     */
    public static function is_int($val) {
        return !(filter_var($val, FILTER_VALIDATE_INT) === false);
    }

    /**
     * Returns a filtered integer value, e.g. `1e9` becomes `1000000000`.  If the value can't be mapped to an integer
     * `false` is returned.  Best used after calling `is_int()`.
     *
     * @param $val Value to filter
     * @return mixed Filtered integer if valid, `false` if not.
     */
    public static function clean_int($val) {
        return filter_var($val, FILTER_VALIDATE_INT); }
}