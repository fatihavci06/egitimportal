<?php

class Slug
{

  private $text;

  public function __construct($text)
  {
    $this->text = $text;
  }

  public static function slugify($text, string $divider = '-')
  {
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
      return 'n-a';
    }

    return $text;
  }
  function makeUniqueSlug(string $text, array $slugRes): string
  {
    $maxNumber = 0;

    foreach ($slugRes as $row) {
      $lastSlug = $row['slug'];

      if (preg_match('/-(\d+)$/', $lastSlug, $matches)) {
        $number = (int)$matches[1];
        if ($number > $maxNumber) {
          $maxNumber = $number;
        }
      } elseif ($lastSlug === $text && $maxNumber === 0) {
        // tam eşleşen slug varsa (örn: "urun")
        $maxNumber = 0;
      }
    }

    if ($maxNumber > 0 || in_array(['slug' => $text], $slugRes)) {
      return $text . '-' . ($maxNumber + 1);
    }

    return $text;
  }
}
