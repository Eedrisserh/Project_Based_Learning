#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>

#define RND_SIZE 4

/**
 * @my_isdigit function to check if the input from argv is a set of integers or not
 * 
 * @str set of input from argument vector (argv) 
 * @return either 0 if all are integers otherwise returns 1
 **/
int my_isdigit(char *str)
{
    int str_length = strlen(str); 

    for (int i = 0; i < str_length; i++)
    {
        if (!(str[i] >= '0' && str[i] <= '9')) //ASCII in play here
            return 1; //returns 1 if the string contains a character
    }
    return 0; //returns zero if all are integers
}

/**
 * @rnd_nums This function generate 4 digit random numbers
 * 
 * @return an int pointer to the set of numbers generated 
 */
int *rnd_nums()
{
    int *code = malloc(RND_SIZE * sizeof(int));
    int temp[9] = {0};

    srand(time(NULL)); //initialize the random number generator
   
    for (int i = 0; i < RND_SIZE; i++)
    {
        int num = rand() % 10; //generates a random number between 0 - 9

        while(temp[num] == 1){ // if the number has already been generated, generate a new one
            num = rand() % 10;
        }
        code[i] = num;
        temp[num] = 1; // mark the number as generated
    }
    return code;

}

/**
 * @my_str_int_cpy This function is used to convert a string containing
 * integers to an array of integers
 * 
 * @param_1 Is the destination integer array
 * @param_2 Is the source string of integers
 * @return ** Functions does not return anything
 */
void my_str_int_cpy(int* param_1, char* param_2)
{
    int i;
    char temp_str[2] = {0};
    for (i = 0; param_2[i] != '\0'; i++)
    {
        temp_str[0] = param_2[i];
        param_1[i] = atoi(temp_str);
    }
}

int main(int argc, char **argv) 
{
    int *secret_code = malloc(RND_SIZE * sizeof(char));
    int attempts = 10, rounds = 0;
    int *guess = malloc(RND_SIZE * sizeof(int));
    
    if (argc > 2)
    {
        if((my_isdigit(argv[2]) != 0) || (strlen(argv[2]) != 4)){ //ensuring the input from commmand line is a set of integers
            printf("Wrong input %s passed\n", argv[2]);
            secret_code = rnd_nums();
        }
        else
        {
            if(strcmp(argv[1], "-c") == 0) // catching -c option and assigning it to the secret code 
                my_str_int_cpy(secret_code, argv[2]);
                

            else if(strcmp(argv[1], "-t") == 0){ // catching -t optiong and assingning it to attempts
                attempts = atoi(argv[2]);
            if (attempts > 10)
                attempts = 10;
            }
        }
    }
    else
       secret_code = rnd_nums();

    printf("Will you find the secret code?\n");
    printf("Please enter a valid guess\n");

    while (attempts > 0)
    {
        printf("\nRound %d\n", rounds);
        rounds ++;

        int well_placed = 0, misplaced = 0;
        char *buffer = malloc (5 * sizeof(char));
        if (buffer == NULL){
            printf("Memory Allocation failed!\n");
            return 1;
        }        
       
        ssize_t n = read(0, buffer, 5);
        if (n <= 4){ // Checking if the user enters an input or EOF
            attempts --;
            break;
        }
        else
            buffer[n] = '\0';

        int num = atoi(buffer);
        if (num == 0) {
            printf("Wrong input passed.\n");
            attempts --;
            continue;
        }
        else
            my_str_int_cpy(guess, buffer);

        for (int i = 0; i < RND_SIZE; i++) 
        {
            if (secret_code[i] == guess[i])
                well_placed ++;
            else
            {
                for (int j = 0; j < RND_SIZE; j++)
                {
                    if (secret_code[i] == guess[j] && i != j)
                    {
                        misplaced ++;
                        break;
                    }
                }
            }
        }
        if (well_placed == 4)
            {
                printf("Congratz! You did it!\n");
                break;
            }
        else
            printf("Well placed pieces: %d\nMisplaced pieces: %d\n", well_placed, misplaced);
                 attempts --;

        free(buffer);
    }
    free(secret_code);
    free (guess);
    return 0;
}