# --------------> My Mastermind <--------------
***

## Task
The task is to create a code-guessing game similar to the classic board game Mastermind.
The challenge is to design a program that can generate a random secret code,
accept guesses from the player, and provide feedback on the number of well-placed and misplaced
digits in each guess.

## Description
I have implemented a code-guessing game in C that generates a 4-digit secret code randomly and allows
the player to make a limited number of attempts (10 by default) to guess the code.
The game counts the number of well-placed (i.e. in the same position as in the secret code)
and misplaced (i.e. present in the secret code but not in the same position) digits in each guess
and provides this information to the player. The game ends either when the player guesses the secret code
correctly or when the number of attempts is exhausted.

## Installation
To install the project, clone the repository and compile the my_mastermind.c file using the GCC compiler.
```gcc -o Mastermind my_mastermind```

## Usage
To play the game, run the Mastermind executable file and follow the prompts.
```./my_mastermind argument1 argument2```
The game supports the following command-line arguments:

-c: Specify the secret code as an argument. For example: ./Mastermind -c 1234.

-t: Specify the number of attempts as an argument. For example: ./Mastermind -t 5.

Have fun playing the game!

### The Core Team
* Idriss Ibrahim Dodo <edrissebraheem@gmail.com>


<span><i>Made at <a href='https://qwasar.io'>Qwasar SV -- Software Engineering School</a></i></span>
<span><img alt='Qwasar SV -- Software Engineering School's Logo' src='https://storage.googleapis.com/qwasar-public/qwasar-logo_50x50.png' width='20px'></span>
