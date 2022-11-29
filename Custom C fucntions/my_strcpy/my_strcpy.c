#include <string.h>

char* my_strcpy(char* param_1, char* param_2)
{
    int i;
    for (i = 0; i < strlen(param_2); i++)
    {
        param_1[i] = param_2[i];
    }
    return param_1;
}