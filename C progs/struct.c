#include <stdlib.h>
#include <stdio.h>

struct User
{
    char *name;
    char *email;
    int age;
}

struct User *new_user(char *name, char *email, int age)
{
    struct User *user;

    user = malloc(sizeof(struct User));
    if (user == NULL)
    return (NULL);
    user->name = name;
    user->email = email;
    user->age = age;
    return user;
}

int main(void)
{
    struct User *user;

    user = new_user("Idriss", "idriss@maryam.love", 98);
    if (user == NULL)
    return (1);
    printf("User %s created !\n", user->name);
    printf("His email is: %s\n", user->email);
    printf("And he is %d years old\n", user->age);
    return (0);
}