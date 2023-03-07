#include <string.h>
#include <stdio.h>

/*
**
** QWASAR.IO -- my_strchr
**
** @param {char*} param_1
** @param {char} param_2
**
** @return {char*}
**
*/
char* my_strchr(char* param_1, char param_2)
{
	int i = 0, j = 0, k = 0;
 	char* new;
 	int len = strlen(param_1);

  for(i = 0; i < len; i++)
  {
    if(param_1[i] == param_2)
    {
      k += 1;
      while (param_1[i])
      {
        new[j] = param_1[i];
        i++;
        j++;
      }
    }
  }
 /* if (k <= 0)
  {
    return 0;
  } */
  return new;
}

int main ()
{
    printf("%s\n", my_strchr("abcabd", 'b'));
    return 0;
}
