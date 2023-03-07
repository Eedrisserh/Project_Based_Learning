#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#define bufsize 10
/**
 * @brief 
 * 
 * @param str1 
 * @param str2 
 */
void StrCopy(char *str1, char *str2)
{
    int i;

    for (i = 0; str1[i]; i++)
    {
        str2[i] = str1[i];
    }
    str2[i] = '\0';
}

/**int *_recalloc(void *ptr, int oldsize, int newsize)
{
    void *temp;

    if (ptr == NULL)
    {
        return (ptr);
    }
    temp = malloc(newsize * sizeof(char));

    if(temp == NULL)
    {
        perror("malloc failed");
        return (temp);
    }
    strcpy(temp, ptr);
    free(ptr);
    return(temp);
} */

void *_realloc(void *ptr, int old, int new)
 {
 	void *temp;
 	int i, min;
 	
 	if (!ptr)
 		return (malloc(new));
 	else if (new == old)
 		return (ptr);
 	else if (new == 0 && ptr)
 	{
 		free (ptr);
 		return (NULL);
	}
	else
	{
		min = (new < old) ? new : old;
		temp = malloc(new);
		if (temp)
		{
			for (i = 0; i < min; i++)
				*((char *)temp + i) = *((char *)ptr + i);
			free(ptr);
			return (temp);
		}
		else
			return (NULL);
	}
 }

int main (void)
{
    void *buffer;
    int i, exp_size = bufsize;

    buffer = malloc(bufsize * sizeof(char));

    if (buffer == NULL)
    {
        printf("Error: Failed to allocate memory");
        exit(EXIT_FAILURE);
    }
    printf ("Enter strings here:\n");
    fgets(buffer, bufsize, stdin);

    if (strlen(buffer) >= bufsize)
    {
        printf("Buffer too short\n");
        printf("Expanding buffer\n");
        exp_size += strlen(buffer);
        buffer = (char *) realloc(buffer, exp_size);
        if (buffer == NULL)
        {
            printf("Failed to reallocate memory\n");
            exit(EXIT_FAILURE);
        }
        printf("Reallocated memory for buffer\n. The new buffer is :\t %s\n", buffer);
    }
    else
    {
        printf("No need to allocate memory\n");
        printf("%c\n", buffer);
    }
    return 0;
}
