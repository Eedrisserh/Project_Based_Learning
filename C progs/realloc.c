#include <stdio.h>
#include <stdlib.h>
#include <string.h>


int main (void)
{
	char *array[3] = {"Let sleeping dogs lie",
			"The quick brown fox jumps over the lazy dog",
			"When a man is faced with his own death, he consider impossible as a less barrier"};
	char *temp, *str;
	int len;

	len = strlen(array[0]) + strlen(array[1]);

	str = malloc (strlen(array[0]) * sizeof(char));

	if(str == NULL)
	{
		perror("MALLOC FAILED");
		exit(-1);
	}

	str = array[0];

	temp = str;

	str = realloc (str, len * sizeof(char));
	
	if (str == NULL)
	{
		perror("REALLOC FAILED");
		exit(EXIT_FAILURE);
	}

	printf("\nThis is the size of temp: size_t and this is str: %size_t",strlen(temp), strlen(str));
	
	free(str);
	return 0;
}
