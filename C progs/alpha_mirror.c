#include <stdio.h>
#include <string.h>

char* alpha_mirror(char* param_1)
{
    int i = 0;
    char *Up_alpha[2] ={"ABCDEFGHIJKLM",
                        "ZYXWVUTSRQPON"};
                       
    char *low_alpha[2] ={"abcdefghijklm",
                         "zyxwvutsrqpon"};

    while(param_1[i] != '\0')
    {
        if (param_1[i] >= 65 && param_1[i] <= 90) //checking for capital letters
        {
            for (int j = 0; j < strlen(Up_alpha[0]); j++)
            {
                if (param_1[i] == Up_alpha[0][j]){
                    param_1[i] = Up_alpha[1][j];
                }
                else if (param_1[i] == Up_alpha[1][j]){
                    param_1[i] = Up_alpha[0][j];
                }
            }
        }
        else if (param_1[i] >= 97 && param_1[i] <= 122) //checking for small letters
        {
            for (int j = 0; j < strlen(low_alpha[0]); j++)
            {
                if (param_1[i] == low_alpha[0][j]){
                    param_1[i] = low_alpha[1][j];
                }
                else if (param_1[i] == low_alpha[1][j]){
                    param_1[i] = low_alpha[0][j];
                }
            }
        }
        i++;
    }
    return param_1;
}

int main(){
    char param_1[] = "My horse is Amazing.";
    printf("%s\n",alpha_mirror(param_1));
    // printf("%s\n", param_1);
    return 0;
}