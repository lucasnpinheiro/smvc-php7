<?php
namespace Helpers;

class Watchlist {

    public static function add($element, $label = null, $returnStack = false, $clearStack = false): void {

        static $stack; //main array holding the actual data. 
        if ($returnStack) {
            return $stack;
        } else if ($clearStack) {
            $stack = '';
        } else {
            if (is_array($element) || is_object($element))
                $stack[] = '<pre>' . $label . ': ' . print_r($element, true) . '</pre>';
            else
                $stack[] = $label . ' : ' . $element;
        }
        
    }

    public static function printList(): void {
        

        $stack = self::add(null, null, true);       

        if (count($stack) > 0) {
            echo '<table border=1 align="center" cellpadding="5" id="Helpers_Watchlist_Table" class="Helpers_Watchlist_Table">';
            echo '<tr><td><b>Helpers Watch List</b></td></tr>';


            for ($i = 0; $i < count($stack); $i++) {
                echo '<tr><td>' . $stack[$i] . '</td></tr>';
            }
            echo '</table>';
        }
    }

    public static function clear(): void {
        self::add(null, null, false, true);
    }

}

