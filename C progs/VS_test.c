#include <stdio.h>
#include <stdlib.h>

int main(void)
{
  struct StudentData
  {
    char *name;
    int age;
    float gpa;
    int score;
  }student, graduate;

  student.name = malloc(20 * sizeof(char));
    
  printf("Please enter your name\n");
  scanf("%s", &student.name);
  printf("Please enter your age\n");
  scanf("%d", &student.age);
  printf("Please enter your score\n");
  scanf("%d", &student.score);

  printf("Student Name: %s\n",student.name);
  printf("Student age: %d\n", student.age);
  printf("Student score: %d\n", student.score);
  graduate.gpa = 3.41;

  printf("Student gpa: %f\n", graduate.gpa);
}