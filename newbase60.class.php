<?php

/**
 * A pure PHP implementation of NewBase60. A base 60 numbering system designed for 
 * use with URL shortening. Limited/overlapping character set to avoid confusion
 * between similar characters, eg o/0, i/l, etc.
 *
 * Q: Why not use PHP base_convert()?
 * A: Because it only goes up to base 36, and doesn't support the  NewBase60
 *
 * @see http://tantek.pbworks.com/w/page/19402946/NewBase60
 * @see http://en.wikipedia.org/wiki/sexagesimal
 *
 * @author david@dsingleton.co.uk
 */

class NewBase60
{
    protected static $characterSet =  '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ_abcdefghijkmnopqrstuvwxyz';
    
    /**
     * Convert a sexagesimal number to a decimal number
     * @param string sexagesimal number to convert
     * @return integer Decimal representation of sexagesimal number
     */
    public static function toDecimal($sexNum)
    {
        // Return falsy and 0 values as is
        if (!$sexNum) {
            return $sexNum === '0' ? 0 : $sexNum;
        }
        
        $decNum = 0;
 
        foreach(str_split($sexNum) as $chr) {
            
            $ord = ord($chr);
            
            if ($ord>=48 && $ord<=57)       { $ord -= 48; } // 0 - 9
            elseif ($ord>=65 && $ord<=72)   { $ord -= 55; } // A - H
            elseif ($ord==73 || $ord==108)  { $ord =  1; }  // Error correct typo: capital I, lowercase l to 1
            elseif ($ord>=74 && $ord<=78)   { $ord -= 56; } // J - N
            elseif ($ord==79)               { $ord =  0; }  // Error correct typo: capital O to 0
            elseif ($ord>=80 && $ord<=90)   { $ord -= 57; } // P - Z
            elseif ($ord==95)               { $ord =  34; } // underscore
            elseif ($ord>=97 && $ord<=107)  { $ord -= 62; } // a - k
            elseif ($ord>=109 && $ord<=122) { $ord -= 63; } // m - z
            else { $ord = 0; }                              // treat all other noise as 0
            $decNum = 60 *$decNum + $ord;
        }
        
        return $decNum;
    }
    
    /**
     * Convert a decimal number to a sexagesimal number
     * @param integer Decimal number to convert
     * @return string sexagesimal representation of decimal
     */
    public static function fromDecimal($decNum)
    {
        $decNum = (int) $decNum;
        
        if (!$decNum) {
            return $decNum === 0 ? '0' : $sexNum;
        }
        
        $aSexCharset = self::$characterSet;
        $result = '';

        while ($decNum > 0) {
          $decRemainder = $decNum % 60;
          $decNum = ($decNum - $decRemainder) / 60;
          $result = $aSexCharset[$decRemainder] . $result;
        }
        
        return $result;
    }
}