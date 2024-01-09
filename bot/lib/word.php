<?php 

	function latinToCrill($word) {
		// ============================ BIG LATTER

		$word = str_replace("SH","Ш",$word);
		$word = str_replace("Sh","Ш",$word);
		$word = str_replace("CH","Ч",$word);
		$word = str_replace("Ch","Ч",$word);
		$word = str_replace("O'","Ў",$word);
		$word = str_replace("O`","Ў",$word);
		$word = str_replace("G`","Ғ",$word);
		$word = str_replace("G'","Ғ",$word);
		$word = str_replace("A","А",$word);
		$word = str_replace("B","Б",$word);
		$word = str_replace("D","Д",$word);
		$word = str_replace("E","Е",$word);
		$word = str_replace("F","Ф",$word);
		$word = str_replace("G","Г",$word);
		$word = str_replace("H","Ҳ",$word);
		$word = str_replace("I","И",$word);
		$word = str_replace("J","Ж",$word);
		$word = str_replace("K","К",$word);
		$word = str_replace("L","Л",$word);
		$word = str_replace("M","М",$word);
		$word = str_replace("N","Н",$word);
		$word = str_replace("O","О",$word);
		$word = str_replace("p","П",$word);
		$word = str_replace("Q","Қ",$word);
		$word = str_replace("R","Р",$word);
		$word = str_replace("S","С",$word);
		$word = str_replace("T","Т",$word);
		$word = str_replace("U","У",$word);
		$word = str_replace("V","В",$word);
		$word = str_replace("X","Х",$word);
		$word = str_replace("Y","Й",$word);
		$word = str_replace("Z","З",$word);

		// ============================ SMALL LATTER
		
		$word = str_replace("o'","ў",$word);
		$word = str_replace("o`","ў",$word);
		$word = str_replace("g`","ғ",$word);
		$word = str_replace("g'","ғ",$word);
		$word = str_replace("sh","ш",$word);
		$word = str_replace("ch","ч",$word);
		$word = str_replace("a","а",$word);
		$word = str_replace("b","б",$word);
		$word = str_replace("d","д",$word);
		$word = str_replace("e","е",$word);
		$word = str_replace("f","ф",$word);
		$word = str_replace("g","г",$word);
		$word = str_replace("h","ҳ",$word);
		$word = str_replace("i","и",$word);
		$word = str_replace("j","ж",$word);
		$word = str_replace("k","к",$word);
		$word = str_replace("l","л",$word);
		$word = str_replace("m","м",$word);
		$word = str_replace("n","н",$word);
		$word = str_replace("o","о",$word);
		$word = str_replace("p","п",$word);
		$word = str_replace("q","қ",$word);
		$word = str_replace("r","р",$word);
		$word = str_replace("s","с",$word);
		$word = str_replace("t","т",$word);
		$word = str_replace("u","у",$word);
		$word = str_replace("v","в",$word);
		$word = str_replace("x","х",$word);
		$word = str_replace("y","й",$word);
		$word = str_replace("z","з",$word);
		$word = str_replace("'","ъ",$word);


		
		return $word;
	}
	function crillToLatin($word) {
		// ============================ BIG LATTER

		$word = str_replace("Ш","SH",$word);
		$word = str_replace("Ч","CH",$word);
		$word = str_replace("Ў","O'",$word);
		$word = str_replace("Ғ","G'",$word);
		$word = str_replace("А","A",$word);
		$word = str_replace("Б","B",$word);
		$word = str_replace("Д","D",$word);
		$word = str_replace("Е","E",$word);
		$word = str_replace("Ф","F",$word);
		$word = str_replace("Г","G",$word);
		$word = str_replace("Ҳ","H",$word);
		$word = str_replace("И","I",$word);
		$word = str_replace("Ж","J",$word);
		$word = str_replace("К","K",$word);
		$word = str_replace("Л","L",$word);
		$word = str_replace("М","M",$word);
		$word = str_replace("Н","N",$word);
		$word = str_replace("О","O",$word);
		$word = str_replace("П","p",$word);
		$word = str_replace("Қ","Q",$word);
		$word = str_replace("Р","R",$word);
		$word = str_replace("С","S",$word);
		$word = str_replace("Т","T",$word);
		$word = str_replace("У","U",$word);
		$word = str_replace("В","V",$word);
		$word = str_replace("Х","X",$word);
		$word = str_replace("Й","Y",$word);
		$word = str_replace("З","Z",$word);

		// ============================ SMALL LATTER
		
		$word = str_replace("ў","o'",$word);
		$word = str_replace("ғ","g'",$word);
		$word = str_replace("ш","sh",$word);
		$word = str_replace("ч","ch",$word);
		$word = str_replace("а","a",$word);
		$word = str_replace("б","b",$word);
		$word = str_replace("д","d",$word);
		$word = str_replace("е","e",$word);
		$word = str_replace("ф","f",$word);
		$word = str_replace("г","g",$word);
		$word = str_replace("ҳ","h",$word);
		$word = str_replace("и","i",$word);
		$word = str_replace("ж","j",$word);
		$word = str_replace("к","k",$word);
		$word = str_replace("л","l",$word);
		$word = str_replace("м","m",$word);
		$word = str_replace("н","n",$word);
		$word = str_replace("о","o",$word);
		$word = str_replace("п","p",$word);
		$word = str_replace("қ","q",$word);
		$word = str_replace("р","r",$word);
		$word = str_replace("с","s",$word);
		$word = str_replace("т","t",$word);
		$word = str_replace("у","u",$word);
		$word = str_replace("в","v",$word);
		$word = str_replace("х","x",$word);
		$word = str_replace("й","y",$word);
		$word = str_replace("з","z",$word);
		$word = str_replace("ъ","'",$word);


		
		return $word;
	}



?>