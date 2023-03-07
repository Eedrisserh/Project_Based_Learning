#include <stdio.h>
#include <string.h>

int max_consecutive_answers(char* answerKey, int k) {
	
    int len = strlen(answerKey);
    int left = 0, right = 0, num_changes = 0, max_length = 0;
    
    while (right < len) {
        if (right > 0 && answerKey[right] != answerKey[right-1]) {
            if (num_changes < k) {
                num_changes++;
            } 
			else 
			{
                max_length = (right - left > max_length) ? right - left : max_length;
                left = right - 1;
                while (left > 0 && answerKey[left] == answerKey[left-1]) {
                    left--;
                }
                num_changes = 1;
            }
        }
        right++;
    }
    max_length = (right - left > max_length) ? right - left : max_length;
    return max_length;
}

int main() {
    char answerKey[] = "TTFF";
    int k = 2;
    int result = max_consecutive_answers(answerKey, k);
    printf("Result: %d\n", result);
    return 0;
}
