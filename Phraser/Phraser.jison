/* description: Parses words out of html, ignoring html in the parse, but returning it in the end */
//option namespace:Phraser
//option class:Phraser
//option fileName:Phraser.php
//option extends:Base

/* lexical grammar */
%lex
%%
"<"(.|\n)*?">"+ 					return 'TAG'
(\w|\d)+							return 'WORD'
(.|\n|\s)							return 'CHAR'
<<EOF>>								return 'EOF'


/lex

%start html

%% /* language grammar */

html
 : contents EOF
     {return $1;}
 ;

contents
 : content
	{
		//js
			$$ = $1;

		//php $$ = $1->text;
	}
 | contents content
	{
		//js
			$$ =  $1 + $2;
		//php $$ = $1->text . $2->text;
	}
 ;

content
	: TAG
		{
			//js
				$$ = Phraser.tagHandler($1);
			//php $$ = $this->tagHandler($1);
		}
	| WORD
		{
			//js
				$$ = Phraser.wordHandler($1);
			//php $$ = $this->wordHandler($1);
		}
	| CHAR
		{
			//js
				$$ = Phraser.charHandler($1);
			//php $$ = $this->charHandler($1);
		}
 ;
