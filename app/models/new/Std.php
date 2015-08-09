<?php
class Std {
        public function recursive_array_search($needle,$haystack) {
            foreach($haystack as $key=>$value) {
                $current_key=$key;
                if($needle===$value OR (is_array($value) && $this->recursive_array_search($needle,$value) !== false)) {
                    return $current_key;
                }
            }
            return false;
        }

        // From here: https://gist.github.com/branneman/951847
        // Thanks to branneman
        // array_search function with partial match
        public function array_find($needle, array $haystack) {
            foreach ($haystack as $key => $value) {
                if (false !== stripos($value, $needle)) {
                    return true;
                }
            }
            return false;
        }
}
