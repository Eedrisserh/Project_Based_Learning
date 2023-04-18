/*
**
** QWASAR.IO -- brackets
**
** @param {char*} param_1
**
** @return {int}
**
*/
#include <string.h>
#include <stdlib.h>
#include <stdio.h>
int brackets(char* param_1)
{
  if (param_1 == NULL)
    return 1;
  

    int len = strlen(param_1);
    char* brack = malloc(len * sizeof(char));
    int j = 0;
  for(int i = 0; i < len; i++){
    if((param_1[i] == '(') || (param_1[i] == ')') || (param_1[i] == '[') || (param_1[i] == ']') || (param_1[i] == '{') || (param_1[i] == '}'))
      brack[j++] = param_1[i];      
  }
  brack[j] = '\0';
  
  j = 0;
  while (brack[j] != '\0'){
    if ((brack[j] == '(') && ((brack[j + 1] != '\0') && ((brack[j + 1] == '}') || (brack[j + 1] == ']')))){
		return 0;
	}
    else if ((brack[j] == '[') && ((brack[j + 1] != '\0') && ((brack[j + 1] == '}' || (brack[j + 1] == ')'))))){
      return 0;
  }
	else if ((brack[j] == '{') && ((brack[j + 1] != '\0') && ((brack[j + 1] == ']' || (brack[j + 1] == ')'))))){
		return 0;
	}
      j++;
  }
 return 1;
}

int main(){
	char* input = "";
	
	printf("%d", brackets(input));
	return 0;
}