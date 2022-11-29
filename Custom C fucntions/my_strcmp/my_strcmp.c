#include <string.h>
// • 0, if the s1 and s2 are equal;
// • a negative value if s1 is less than s2;
// • a positive value if s1 is greater than s2.

int my_strcmp(char* param_1, char* param_2)
{
    int i, count = 0;
    for (i = 0; i < strlen(param_1); i++)
    {
        if (param_1[i] != param_2[i])
        {
            count ++;
            return param_1[i] - param_2[i];
        }
    }
    if (count == 0)
    {
        return 0;
    }
}