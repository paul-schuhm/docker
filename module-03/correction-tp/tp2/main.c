#include <stdio.h>
#include <stdlib.h>
#include <time.h>

int main()
{
    int max = 100;
    int randomNumber;
    int maxTries = 10;
    int guess;
    int try = 0;
    int currentTime = time(NULL);
    //Initialisation du générateur de nombre aléatoires
    srand(currentTime);
    //Nombre aléatoire entre 0 et 100
    randomNumber = rand() % max;
    do
    {
        printf("Enter your guess (between 0 and %d): ", max);
        scanf("%d", &guess);

        if (guess > randomNumber)
        {
            printf("Too high! Try again.\n");
        }
        else if (guess < randomNumber)
        {
            printf("Too low! Try again.\n");
        }
        else
        {
            printf("Congratulations! You guessed the number\n");
        }

        try++;
    } while (guess != randomNumber && try < maxTries );
}