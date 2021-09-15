const puppeteer = require('puppeteer');
const fs = require('fs');
const convert = require('xml-js');
const crypto = require('crypto-js');
const trans = require('cyrillic-to-translit-js');
const _ = require('lodash');

(async () => {
    const browser = await puppeteer.launch({
        args: [
            '--disable-web-security',
            '--disable-features=IsolateOrigins,site-per-process',
            '--window-size=1920,1080',
        ],
    });

    await complex(browser, 'https://magistrat-don.ru/object/jk-5-element/', '5 элемент');
})();

async function complex(browser, url, complexName) {
    const page = await browser.newPage();
    await page._client.send('Emulation.clearDeviceMetricsOverride');

    await page.goto(url).catch((...args) => {
        console.log(args);
    });

    console.log('Открыл страницу');

    await page.waitForSelector('.novostroy_in_table');

    console.log('Загрузилась таблица');

    await build(page, complexName);

    await page.close();
    await browser.close();
    console.log('browser closed');
}

async function build(page, complexName) {
    const complexes = {complexes: {complex: []}};
    const complex = {};

    complex.id = crypto.MD5(complexName).toString();
    complex.name = complexName;

    complex.buildings = await page.evaluate(() => {
        const buildings = {building: []};
        const building = {};

        building.flats = {flat: []};

        let body = document.querySelector('.body-big');

        body.querySelectorAll('tr').forEach((element, index) => {

            let info = element.getAttribute('onclick');
            info = info.split(',');

            let number = info[4].replace(/"/g, '');
            let img = info[9].replace(/"/g, '');

            let td = element.querySelectorAll('td');

            let rooms = td[3].textContent.replace(/\D/gu, '');
            let area = td[4].textContent.replace(/[^0-9\.]+/gu, '');
            let price = td[5].textContent.replace(/\D/gu, '');

            buildings.building.push(
                {
                    number: number,
                    rooms: rooms,
                    price: price,
                    area: area,
                    plan: 'https://magistrat-don.ru' + img,
                    name: td[0].textContent,
                }
            );
        });

        return buildings
    });

    complex.buildings.building = _.chain(complex.buildings.building)
        .groupBy('name')
        .map((value, key) =>
            (
                {
                    id: crypto.MD5(key).toString(),
                    name: key,
                    flats: {
                        flat: value
                    }
                }
            ))
        .value()

    for(let i = 0; i < complex.buildings.building.length; i++){
        for(let j = 0; j < complex.buildings.building[i].flats.flat.length; j++) {
            delete complex.buildings.building[i].flats.flat[j].name;
        }
    }

    complexes.complexes.complex.push(complex);

    let options = {compact: true, ignoreComment: false, spaces: 4};
    let result = convert.js2xml(complexes, options);

    fs.writeFileSync(
        __dirname + '/../public/storage/xml/magistrat-don:' +
        trans().transform(complex.name, '-').toLowerCase() + '.xml',
        '<?xml version="1.0" encoding="UTF-8" ?>\n' + result
    );

}
