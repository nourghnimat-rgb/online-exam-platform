<?php

namespace App\Enums;

enum QuestionType: string
{
    case SingleChoice='single_choice';
    case MultipleChoice='multiple_choice';
    case ShortText='short_text';

    public function hasOptions(){
        return $this!== self::ShortText;
    }
}
