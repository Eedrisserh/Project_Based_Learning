/*
**
** QWASAR.IO -- my_union
**
** @param {char*} param_1
** @param {char*} param_2
**
** @return {char*}
**
*/
#include <string.h>
#include <stdlib.h>
#include <stdio.h>

char* my_union(char* param_1, char* param_2)
{
    char* strings = malloc((strlen(param_1) > strlen(param_2) ? strlen(param_1) : strlen(param_2)) * sizeof(char));
    if (strings == NULL){
        printf("Memory allocation failed");
    }
    char result[128] = {0};
    int i = 0;
    int x = 0;

    while(param_1[i] !='\0'){
        result[(int)param_1[i]] += 1;
        if(result[param_1[i]] == 1)
            strings[x++] = param_1[i];
            i++;
    }
    
    int j = 0;
     while(param_2[j] !='\0'){
        result[(int)param_2[j]] += 1;
        if(result[param_2[j]] == 1)
            strings[x++] = param_2[j];
            j++;
    }
    strings[x] = '\0';

    return strings;
}

int main(){
    char* str1 = "abbca";
    char* str2 = "def";
    
    // char* result = my_union(str1, str2);
    // printf("%s\n", result);
    printf("%c\n", 'Z' - 25);
    
    return 0;
}