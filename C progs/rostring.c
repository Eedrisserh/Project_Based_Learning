#include <stdio.h>
#include <stdlib.h>
#include <string.h>


char* rostring(char* str){
	if (strlen(str) < 1)
      return str;
		
	int begin, end, i = 0, j = 0;
	char* rostring = malloc((strlen(str) + 1) * sizeof(char));
	
	
	while(!isalpha(str[i])){
		i++;
	}
	begin = i;
		while(isalpha(str[i])){
		i++;
	}
	end = i;
	
	char* word = malloc((end - begin) * sizeof(char));
	
	for(begin; begin < end; begin++){
		word[j++] = str[begin];
	}
	j = 0;

	for(end++; end < strlen(str); end++){
		rostring[j++] = str[end];
	}
	rostring[j] = ' ';
	strcat(rostring, word);
  	j = 0;
    while(isspace(rostring[j])){
      *rostring++;
    }
	return rostring;
}

int main(){
	char* input =  "  AkjhZ zLKIJz , 23y";
//	char* input = "abc   ";
	printf("%s\n", rostring(input));
	
	return 0;
}