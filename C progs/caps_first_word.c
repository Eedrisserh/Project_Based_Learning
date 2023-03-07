#include <stdio.h>
#include <string.h>
#include <ctype.h>
#include <stdlib.h>

char *capitalize(char *input_string) {
    char *result = (char*)malloc(strlen(input_string) + 1);
    char *word, *sep;
    char *delim = " \t\n\v\f\r";
    int i;

    word = strtok(input_string, delim);
    sep = input_string + strlen(word);
    while (word != NULL) {
        for (i = 0; word[i]; i++) {
            word[i] = tolower(word[i]);
        }
        word[0] = toupper(word[0]);
        strcat(result, word);
        strcat(result, sep);
        word = strtok(NULL, delim);
        sep = input_string + strlen(result);
    }

    return result;
}

int main() {
    char input_string[] = "a FiRSt LiTTlE TESt";
    char *result = capitalize(input_string);
    printf("%s\n", result);
    free(result);
    return 0;
}
