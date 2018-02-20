<?php

class Slug {
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->class_dependencies = [
            'DB',
        ];

        foreach ($this->class_dependencies as $class) {
            $this->{$class} = null;
            if (isset($parameters[$class])) $this->{$class} = $parameters[$class];
        }
    }

    /**
    * Sanitizes a title, replacing whitespace and a few other characters with
    * dashes.
    *
    * Limits the output to alphanumeric characters and dash (-)
    * Whitespace becomes a dash
    *
    * NOTE: duplicate slugs are not dealt with in this method. See get_unique().
    *
    * @see get_unique()
    * @param string $title The title to be sanitized.
    * @return string The sanitized title.
    */
    public function slugify($title) {
        $title = strip_tags($title);
        $title = strtolower($title);

        // Remove inter-string apostrophes and semicolons
        $title = str_replace(['\'', ';'], '', $title);

        // Remove stopwords
        $title = $this->remove_stopwords($title);

        // Kill entities
        $title = preg_replace('/&.+?;/', '', $title);

        // Replace periods and underscores with dashes
        $title = str_replace(['.', '_'], '-', $title);

        // Replace foreign characters with English ones
        $title = $this->normalize($title);

        // Remove all characters we don't want
        $title = preg_replace('/[^a-z0-9 -]/', '', $title);

        // Filter multiple spaces and dashes
        $title = preg_replace('/\s+/', '-', $title);
        $title = preg_replace('|-+|', '-', $title);

        // Remove leading & trailing dashes
        $title = trim($title, '-');

        return $title;
    }

    /**
    * Removes known SEO stopwords from a string
    *
    * @param string $str The string to be sanitized.
    * @return string The string without stopwords.
    */
    private function remove_stopwords($str) {
        $stopwords = [
            'a',
            'and',
            'the',
            'for',
            'from',
            'go',
            'on',
            'it',
            'is',
            'an',
            'how',
            'to',
            'do',
            'does',
            'any',
            'are',
            'be',
            'also',
            'etc',
            'with',
            'in',
            'of'
        ];

        $stopword_string = implode('|', $stopwords);

        return preg_replace('/\b(' . $stopword_string . ')\b/', '', $str);
    }

    /**
    * Replaces "foreign" characters with "English" ones
    *
    * @param string $str The string to be sanitized.
    * @return string The string without foreign characters.
    */
    private function normalize($str) {
        // Turkish chars are a special case
        $str = strtolower($this->transliterate_turkish_chars($str));

        // So are some ligatures
        $str = strtolower($this->transliterate_ligatures($str));

        $str = htmlentities($str, ENT_QUOTES, 'UTF-8');
        $str = preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $str);
        $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
        $str = preg_replace(array('~[^0-9a-z]~i', '~[ -]+~'), ' ', $str);

        return $str;
    }

    /**
    * Replaces Turkish characters not caught by normalize()
    *
    * @param string $str The string to be sanitized.
    * @return string The string without Turkish characters.
    */
    private function transliterate_turkish_chars($str) {
        $search  = ['ç', 'Ç', 'ğ', 'Ğ', 'ı', 'İ', 'ö', 'Ö', 'ş', 'Ş', 'ü', 'Ü'];
        $replace = ['c', 'C', 'g', 'G', 'i', 'I', 'o', 'O', 's', 'S', 'u', 'U'];
        return str_replace($search, $replace, $str);
    }

    /**
    * Transliterates uncommon, mostly Nordic ligatures not caught by normalize()
    *
    * @param string $str The string to be sanitized.
    * @return string The string with its ligatures transliterated.
    */
    private function transliterate_ligatures($str) {
        $search  = ['ﬁ',  'ﬂ',  'ﬀ', 'ﬃ', 'f‌f‌l', 'ﬆ',  'ﬅ',  'ƣ', '₧',   '₣',  'უ',   'ო',    'ჳ',  'Ꜩ',  'ꜩ', 'Ꜳ',  'ꜳ', 'œ', 'Œ',   'Æ',  'æ',  'Ĳ',  'ĳ',  'ᵫ', 'Ꜵ',  'ꜵ', 'Ꜷ', 'ꜷ', 'Ꜹ',  'ꜹ', 'Ꜽ',  'ꜽ', 'Ꝏ', 'ꝏ', 'ﬅ',  'ﬆ',  'Ø', 'Å'];
        $replace = ['fi', 'fl', 'ff', 'ffi', 'ffl', 'st', 'ft', 'Oi', 'Pts', 'Fr', 'uni', 'oni', 'vie', 'TZ', 'tz', 'AA', 'aa', 'oe', 'OE', 'AE', 'ae', 'IJ', 'ij', 'ue', 'AO', 'ao', 'AJ', 'aj', 'AV', 'av', 'AY', 'ay', 'OO', 'oo', 'ft', 'st', 'O', 'A'];
        return str_replace($search, $replace, $str);
    }

    /**
     * Takes a slug and make sure it's unique within a given table for a given
     * operation type or user.  If it isn't, append a dash and a number to the end of it.
     *
     * Inspiration: http://stackoverflow.com/a/15971929/1760760
     *
     * @param string $title       The title to be slugified
     * @param string $table       The table to search for existing records with this slug
     * @param string $parent_id   The listing/whatever ID for which this slug must be unique - leave it null and the slug must be truly unique across the whole table
     * @param string $parent_type The kind of parent you're supplying an ID for - some possible values: "food_listing_id", etc.
     * @return string A unique slug, ready to be saved to the DB
     * @throws \Exception
     */
    public function get_unique($title, $table, $parent_id = null, $parent_type = null) {
        if (isset($parent_id) && (!ctype_digit($parent_id) && !is_int($parent_id))) {
            throw new \Exception('Cannot create slug; invalid parent ID.');
        }

        if (isset($parent_id) && (!isset($parent_type) || !ctype_alnum(str_replace('_', '', $parent_type)))) {
            throw new \Exception('Cannot create slug; invalid parent type.');
        }

        if (!isset($table) || !ctype_alnum(str_replace('_', '', $table))) {
            throw new \Exception('Cannot create slug; invalid entity.');
        }

        // Create a slug based on the title
        $slug = $this->slugify($title);

        // If $parent_id was specified, make sure the slug is unique for that parent
        if (isset($parent_id)) {
            $bind = [
                'slug' => $slug,
                'parent_id' => $parent_id
            ];

            $highest_match = $this->DB->run("
                SELECT slug 
                FROM {$table} 
                WHERE {$parent_type}=:parent_id 
                    AND (slug=:slug OR slug REGEXP '{$slug}-[0-9]*') 
                ORDER BY LENGTH(slug) DESC, slug DESC LIMIT 1
            ", $bind);
        } else {
            // If $parent_id was not supplied, make sure the slug is unique for
            // the entire table
            $bind = [
                'slug' => $slug
            ];

            $highest_match = $this->DB->run("
                SELECT slug 
                FROM {$table} 
                WHERE (slug=:slug OR slug REGEXP '{$slug}-[0-9]*') 
                ORDER BY LENGTH(slug) DESC, slug DESC LIMIT 1
            ", $bind);
        }

        // A record with this slug already exists
        if (isset($highest_match[0]['slug'])) {
            $parts = explode('-', $highest_match[0]['slug']);
            $num = (int) end($parts);
            $num = (ctype_digit($num) || is_int($num) ? $num : 0);

            if ($num == 0) {
                // Start the numbering at 2
                // If neongreens exists, we want the next slug to be neongreens-2
                $num = 2;
            } else {
                $num++;
            }

            $slug = $slug . '-' . $num;
        }

        return $slug;
    }

    /**
    * Constructs a profile slug
    *
    *   - Attempt to concatenate words
    *   - Try an acronym
    *   - Use hyphens
    *   - Append a counter
    *
    * Checks for duplicates
    *
    * @param string $name The name to be slugified
    * @param string $table The table to check for availability
    * @return string The slugified name
    */
    public function slugify_name($name, $table, $parent_id, $parent_type) {
        $slug_hyphenated = $this->slugify($name);
        $slug_concatenated = str_replace('-', '', $slug_hyphenated);
        $slug_acronym = preg_replace('~\b(\w)|.~', '$1', str_replace('-', ' ', $slug_hyphenated));

        // Preferred slugs in order of decreasing preference
        $slugs = [
            $slug_concatenated,
            $slug_hyphenated,
            $slug_acronym
        ];

        // Prefer the acronym for orgs with crazy-long names
        /*if (strlen($slug_concatenated) > 15) {
            $slugs[0] = $slug_acronym;
            $slugs[1] = $slug_concatenated;
        }*/

        // Try each preferred slug
        foreach ($slugs as $slug) {
            $available = $this->slug_available($slug, $table);

            if ($available === true) {
                return $slug;
            }
        }

        // If they're all taken, append a counter to the first choice
        return $this->get_unique($slugs[0], $table, $parent_id, $parent_type);
    }

    /**
    * Tells us if an organization slug is available for registration.  
    * 
    * There are two components to this check:
    *
    *   1) Is this slug reserved?
    *   2) Does a DB record exist for an profile with this slug?
    *
    * @param string $slug The slug to be checked
    * @return bool True if the slug is available for registration, false if not
    */
    public function slug_available($slug, $table) {
        // Block all single-character requests
        if (strlen($slug) <= 1) {
            return false;
        }

        // Check against string + int subdomains: cdn0, www4, ns3, demo3, etc.
        $string_int = ['cdn', 'demo', 'mail', 'ns', 'test', 'www', 'ww'];
        foreach ($string_int as $s) {
            $remainder = str_replace($s, '', $slug);

            if ($remainder == '' || ctype_digit($remainder)) {
                return false;
            }
        }

        // Check against DB to make sure this org slug doesn't already exist
        $bind = [
            'slug' => $slug
        ];

        $result = $this->DB->run('
            SELECT id 
            FROM {$table} 
            WHERE slug=:slug 
            LIMIT 1
        ', $bind);

        if (isset($result[0]['id'])) {
            return false;
        }

        return true;
    }

    /**
     * Valid slugs must contain only letters, numbers, and dashes, and must
     * not begin or end with a dash.
     *
     * @param string $slug The slug to check
     * @return boolean True if valid, false if not
     */
    public function validate($slug) {
        if (ctype_alnum(str_replace('-', '', $slug)) === false) {
            return false;
        }

        if ($slug[0] == '-' || mb_substr($slug, -1) == '-') {
            return false;
        }

        return true;
    }
}

?>