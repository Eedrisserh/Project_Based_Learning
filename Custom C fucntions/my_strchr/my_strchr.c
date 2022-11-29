#include <string.h>
#include <stdlib.h>

char* my_strchr(char* param_1, char param_2)
{
    char *temp = calloc(strlen(param_1), sizeof(char));
    int count = 0;
    for (int i = 0; i < strlen(param_1); i++)
    {
        if (param_1[i] == param_2)
        {
            int j;
            count ++;
            for (j = 0; i < strlen(param_1); j++)
            {
                temp[j] = param_1[i];
                i++;
            }
        }
    }
    if (count == 0)
    return NULL;

return temp;
}