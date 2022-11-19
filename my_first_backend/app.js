const express = require('express');
const app = express();
const BasicAuthen = require('express-basic-auth')



app.get('/', (req, res) =>
{
    let songs = ['Ac-cent-tchu-ate the Positive', 'Accidents Will Happen', 'Ad-Lib Blues',
'Air For English Horn', 'Blue Lace', 'Bop Goes My Heart', 'But None Like You',
'The Call of the Canyon', "Can't You Just See Yourself?", "Come Back to Me",
"Come Blow Your Horn", "The Day After Forever", "Daybreak", "Deep in a Dream",
"Do You Know Why?","You're the One (For Me)", "You're My Girl", "Why Try to Change Me Now?",
"When Somebody Loves You", "When I Stop Loving You", "Sunflower", "Not So Long Ago",
"Moment to Moment", "Love Lies", "Kiss Me Again", "I've Been There", "It Gets Lonely Early",
"I'm Gonna Live Till I Die", "I'll Follow My Secret Heart", "If I Only Had a Match",
"I Tried", "I Loved Her"]
random  = Math.floor(Math.random() * songs.length) + 1;
    res.send(songs[random]);
});

app.get('/birth_date', (req, res) =>
{
    res.send('December 12, 1915');
});

app.get('/birth_city', (req, res) =>
{
    res.send('Hoboken, New Jersey, U.S.');
});

app.get('/wives', (req, res) =>
{
    res.send("Nancy Barbato, Ava Gardner, Mia Farrow, Barbara Marx");
});

app.get('/picture', (req, res) =>
{
    res.redirect('https://en.wikipedia.org/wiki/Frank_Sinatra#/media/File:Frank_Sinatra2,_Pal_Joey.jpg')
});

app.get('/public', (req, res) =>
{
    res.send('Everybody can see this page');
});


app.get('/protected', BasicAuthen(
{
    challenge: true,
    users: {'admin':'admin'},
    unauthorizedResponse:(`401 Not authorized`)
}), (req, res) =>
{
    res.status(200).send('Welcome, authenticated client');
});

app.listen(8080, '0.0.0.0', () =>
{
    console.log("Port 8080 is loud and clear");
});