#include <stdlib.h>
#include <string.h>
#include <stdio.h>


char* last_word(char* param_1){
  if (param_1 == NULL)
    	return NULL;
  
  int i, j;
  int len = strlen(param_1);
  printf("This is len %d\n", len);

	for(i = len; i >= 0; i--){
    	if((param_1[i] >= 'a' && param_1[i] <= 'z') || (param_1[i] >= 'A' && param_1[i] <= 'Z'))
          break;
		}
	
	int last = i;
	printf("This is last word: %c\n", param_1[last]);

	while(i >= 0){
    if (param_1[i - 1] == ' ' || param_1[i - 1] == ',')
      break;
      i--;
  }

  printf("this is first word %c\n", param_1[i]);

	char* last_word = malloc((last - i + 1) * sizeof(char));
           
	for(j = 0; i <= last; i++)
           last_word[j++] = param_1[i];

          last_word[j] = '\0'; 
	  return last_word;
  }
           
           int main(){
            printf("%s\n", last_word("Idriss,Ibrahim dodo,"));
            return 0;
           }