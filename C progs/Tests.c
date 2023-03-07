#include <string.h>
#include <stdio.h>
#include <stdlib.h>


// char* my_putstr(char* param_1)
// {
  
//   char* grt = "Hello ";
//   int len = strlen(grt) + strlen(param_1) + 1;
 
//   char *str = malloc(len * sizeof(char));
//   if (str == NULL)
//   {
//     printf("Couldn't allocate memory for %s\n", str);
//     exit(EXIT_FAILURE);
//   }
//   strcpy(str, grt);
//   strcat(str, param_1);
//  param_1 = str;
 
   
//     return param_1;
//     free(str);
   
// }

char* my_putstr(char* param_1)
{
  
  char* grt = "Hello ";
  int len = strlen(grt) + strlen(param_1) + 1;
  char *temp = malloc(strlen(param_1) * sizeof(char));
  strcpy(temp, param_1);
  // param_1 = realloc(param_1, 0);

  printf("This is temp: %s\n", temp);

  param_1 = malloc(len * sizeof(char));

  if (param_1 == NULL)
  {
    printf("Couldn't allocate memory for %s\n", temp);
    exit(EXIT_FAILURE);
  }
    
   strcpy(param_1, temp);
   printf("contents of param_1 is: %s", param_1);
//   strcat(str, param_1);
//  param_1 = str;
 
     return param_1;
//     free(str);
//    
   
}

int main()
{
 printf("%s\n",my_putstr("Ibrahim"));

  return 0;
}
