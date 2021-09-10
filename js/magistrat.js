const puppeteer = require('puppeteer');
const fs = require('fs');
const convert = require('xml-js');
const crypto = require('crypto-js');
const trans = require('cyrillic-to-translit-js');

(async () => {
    const browser = await puppeteer.launch({
        args: [
            '--disable-web-security',
            '--disable-features=IsolateOrigins,site-per-process',
            '--window-size=1920,1080',
        ],
    });

    const page = await browser.newPage();
    await page._client.send('Emulation.clearDeviceMetricsOverride');

    await page.goto('https://magistrat-don.ru/object/jk-5-element/', {timeout: 360000});

    await page.waitForSelector('.body-big', {timeout: 60000});

    console.log('Выбрал фрейм');

    const complexes = {complexes: {complex: []}};
    const complex = {};

    let names = Array(page.evaluate(() => {
        return document.querySelector('#korpus option').textContent;
    }));
    complex.id = crypto.MD5(complex.name).toString();

    complex.buildings = await page.evaluate(() => {
        const buildings = {building: []};
        for(let i = 1; i < names.length; i++) {
            document.querySelectorAll('tr').forEach((element, index) => {
                const building = {};

                let info = element.getAttribute('onclick');
                info = info.split(',', 1);
                let number = info[4];
                let img = info[9];
                building.flats = {flat: []};

                element.querySelectorAll('td').forEach((node) => {
                    let rooms = node[3].textContent.replace(/\D/gu, '');
                    let area = node[4].textContent.replace(/[^0-9\.]+/gu, '');
                    let price = node[5].textContent.replace(/\D/gu, '');

                    if (names[i] === node[0]) {
                        building.name = node[0];
                        building.id = crypto.MD5(node[0]).toString();
                        building.flats.flat.push({
                            number,
                            rooms,
                            area,
                            price,
                            img,
                        });
                    }

                    buildings.building.push(building);
                });
            });
        }
        return buildings;
    });

    complexes.complexes.complex.push(complex);
    /*
    complex.buildings.building = complex.buildings.building.map((building) => ({
        id: crypto.MD5(building.name).toString(),
        name: building.name,
        flats: building.flats,
    }));
    complexes.complexes.complex.push(complex);

    console.log(complexes);

    let options = {compact: true, ignoreComment: true, spaces: 4};
    let result = convert.js2xml(complexes, options);

    fs.writeFileSync(
        __dirname + '/../public/storage/xml/alfastroyinvest:' +
        trans().transform(complex.name, '-').toLowerCase() + '.xml',
        '<?xml version="1.0" encoding="UTF-8" ?>\n' + result
    );

    console.log('finish', complex.name);
     */

    console.log(complexes);

    await page.close();
    await browser.close();
    console.log('browser closed');
})();
