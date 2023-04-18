#include <string.h>
#include <stdlib.h>

char* my_strrchr(char* param_1, char param_2)
{
    int i, count = 0;
    char *temp = calloc(strlen(param_1), sizeof(char));

    for (i = 0; i <= strlen(param_1); i++)
    {
        if(param_1[i] == param_2)
            count += 1;
    }

    for (i = 0; i <= strlen(param_1); i++)
    {
        if(param_1[i] == param_2 && count > 1)
            count -= 1;
        else if(param_1[i] == param_2 && count == 1)
        {
            int j = 0;
            for (; i <= strlen(param_1); i++){
            temp[j] = param_1[i];
            j++;
            }
        }
    }
    if (count == 0)
        return NULL;
    return temp;
}