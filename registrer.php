<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="styles.css">
    </head>
    <body>
    <form>
        <h1>Registrering</h1>

        <h3>Student</h3>
        <div>
            <h4>Navn</h4>
            <input type="text" name="name" id="name" placeholder="Skriv inn ditt navn" >
        </div>
        <div>
        <h4>Epost</h4>
            <input type="text" name="email" id="email" placeholder="Skriv inn din epostadresse">
        </div>
        <div>
        <h4>Passord</h4>
            <input type="text" name="password" id="password" placeholder="Skriv inn ønsket passord">
        </div>
        <div>
        <h4>Studieretning</h4>
            <select name="course" id="course">
                <option value="informatikk">Informatikk</option>
                <option value="design">Design</option>
                <option value="litteratur">Litteratur</option>
            </select>
        </div>
        <div>
        <h4>Studiekull</h4>
            <select name="year" id="year">
                <option value="2022">2022</option>
                <option value="2022">2023</option>
                <option value="2022">2024</option>
                <option value="2022">2025</option>
            </select>
        </div>
        <div>
            <button>Registrer</button>
        </div>
</form>

        <form>
            <h3>Foreleser</h3>
        <div>
        <h4>Navn</h4>
            <input type="text" name="name" id="name" placeholder="Skriv inn ditt navn" >
        </div>
        <div>
        <h4>Epost</h4>
            <input type="text" name="email" id="email" placeholder="Skriv inn din epostadresse">
        </div>
        <div>
        <h4>Passord</h4>
            <input type="text" name="password" id="password" placeholder="Skriv inn ønsket passord">
        </div>
        <div>
        <h4>Emne</h4>
            <select name="emne" id="emne">
                <option value="informatikk">Informatikk</option>
                <option value="design">Design</option>
                <option value="litteratur">Litteratur</option>
            </select>
        </div>
        <div>
            <h4>Velg bilde</h4>
        <input type="file" name="picture" id="picture">
        </div>
        <div>
            <button>Registrer</button>
        </div>

        </form>
    </body>
</html>