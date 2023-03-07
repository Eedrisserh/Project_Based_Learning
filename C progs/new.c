#include <stdlib.h>
#include <string.h>
#include <stdio.h>
char* last_word(char* param_1){
  if (param_1 == NULL)
    return NULL;
  
  int len = strlen(param_1);
  int i, j, last;
  
  for (i = len; i > 0; i--){
    if((param_1[i] >= 'a' && param_1[i] <= 'z') || (param_1[i] >= 'A' && param_1[i] <= 'Z'))
      break;
  }
  last = i;
  
  while(i > 0){
  	if(param_1[i] == ',' || param_1[i] == ' '){
      i++;
      break;
    }
    i--;
  }
  char* word = malloc( (last - i) * sizeof(char));
  for(j = 0; i <= last; i++)
    word[j++] = param_1[i];
  
  word[j] = '\0';
  
  return word;
}

int main(){
	printf(last_word("Idriss ,Ibrahim     "));
	return 0;
}