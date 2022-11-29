#include <string.h>
char* reverse_string(char* param_1)
{
    char temp;
    int b, len = strlen(param_1);
    int e = len - 1;

    for (b = 0; b < len / 2; b++)
    {
        temp = param_1[b];
        param_1[b] = param_1[e];
        param_1[e] = temp;
        e--;
  }
  return param_1;
}