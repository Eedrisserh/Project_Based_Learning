#include <string.h>
#include <stdio.h>
#include <stdlib.h>


char* my_putstr(char* param_1)
{
  
  char* grt = "Hello ";
  int len = strlen(grt) + strlen(param_1) + 1;
 
  char *str = malloc(len * sizeof(char));
  if (str == NULL)
  {
    printf("Couldn't allocate memory for %s\n", str);
    exit(EXIT_FAILURE);
  }
  strcpy(str, grt);
  strcat(str, param_1);
 param_1 = str;
 
   
    return param_1;
    free(str);
   
}

int main()
{
 printf("%s\n",my_putstr("Ibrahim"));

  return 0;
}
