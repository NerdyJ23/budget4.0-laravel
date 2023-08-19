<?php
namespace App\Enums\Filters;

enum StringFilterType {
	case MATCH_BEFORE; // *johndoe
	case MATCH_AFTER; //johndoe*
	case MATCH_PARTIAL; //*johndoe*
	case MATCH_EXACT; //johndoe
}