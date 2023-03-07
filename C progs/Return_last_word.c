#include <stdio.h>
#include <string.h>
#include <stdlib.h>
/**
 * @brief The function only handles white space and comma as separators
 * 
 * @param param_1 the string to check
 * @return ** char* to the last word of the string
 */
char *return_last_word(char *param_1)
{
  int len = strlen(param_1);
  int i;

  // Find the index of the last non-space character
  for (i = len - 1; i >= 0; i--)
  {
    if (param_1[i] != ' ' && param_1[i] != ',')
    {
      break;
    }
  }

  int word_len = i;
  // Find the index of the beginning of the last word
  for (; i >= 0; i--)
  {
    if (param_1[i] == ' ' || param_1[i] == ',')
    {
      i++; //  lorem,ipsum  //
      break;
    }
  }

  word_len = (word_len - i) + 1; // Find the length of the last word

  // Allocate word_lenmory for the last word
  char *last_word = malloc(word_len + 1);

  // Copy the last word into the allocated word_lenmory
  int k;
  for (k = 0; i < len; i++, k++)
  {
    if (param_1[i] == ' ' || param_1[i] == ',')
      break;
    else
      last_word[k] = param_1[i];
  }
  last_word[k] = '\0';
  return last_word;
}

int main()
{
  char *ret = return_last_word("  lorem,ipsum,  ");
  printf("%s", ret);
  return 0;
}