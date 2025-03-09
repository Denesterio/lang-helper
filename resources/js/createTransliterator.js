export default (langFrom, langTo) => {
    const russian = [
        'й',
        'ц',
        'у',
        'к',
        'е',
        'н',
        'г',
        'ш',
        'щ',
        'з',
        'х',
        'ъ',
        'ф',
        'ы',
        'в',
        'а',
        'п',
        'р',
        'о',
        'л',
        'д',
        'ж',
        'э',
        'я',
        'ч',
        'с',
        'м',
        'и',
        'т',
        'ь',
        'б',
        'ю',
        '.',
        ',',
    ];
    
    const english = [
        'q',
        'w',
        'e',
        'r',
        't',
        'y',
        'u',
        'i',
        'o',
        'p',
        '[',
        ']',
        'a',
        's',
        'd',
        'f',
        'g',
        'h',
        'j',
        'k',
        'l',
        ';',
        "'",
        'z',
        'x',
        'c',
        'v',
        'b',
        'n',
        'm',
        ',',
        '.',
        '/',
        '?',
    ];
    
    const obj = {
        russian,
        english,
    };

    const replacer = Object.fromEntries(obj[langFrom].reduce((acc, current, index) => {
        acc.push([current, obj[langTo][index]]);
        return acc;
    }, []));

    return (str, symbol, index) => {
        if (!symbol) {
            return str;
        }

        const newSymbol =
            symbol.toLowerCase() === symbol
                ? replacer[symbol] || symbol
                : replacer[symbol.toLowerCase()]?.toUpperCase() || symbol;
        return str.substr(0, index) + newSymbol + str.substr(index + 1);
      };
}
