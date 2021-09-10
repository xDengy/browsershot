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

    await complex(browser, 'https://magistrat-don.ru/object/jk-5-element/');
})();

async function complex(browser, url) {
    const page = await browser.newPage();
    await page._client.send('Emulation.clearDeviceMetricsOverride');

    await page.goto(url).catch((...args) => {
        console.log(args);
    });

    console.log('Открыл страницу');

    await page.waitForSelector('.novostroy_in_table');

    console.log('Загрузилась таблица');

    /*
     */
    // тут обработка
    const complexes = {complexes: {complex: []}};
    const complex = {};

    complex.buildings = await page.evaluate(() => {
        let names = document.querySelectorAll('#korpus option');

        const buildings = {building: []};

        const building = {};
        building.flats = {flat: []};

        for (let j = 0; j < names.length; j++) {
            building.name = names[j].textContent;
            let body = document.querySelector('.body-big');
            let tr = body.querySelectorAll('tr');
            for (let i = 0; i < tr.length; i++) {
                let info = tr[i].getAttribute('onclick');
                info = info.split(',');
                let number = info[4].replace(/"/g, '');
                let img = info[9].replace(/"/g, '');
                let td = tr[i].querySelectorAll('td');
                for (let i = 0; i < td.length; i++) {
                    let rooms = td[3].textContent.replace(/\D/gu, '');
                    let area = td[4].textContent.replace(/[^0-9\.]+/gu, '');
                    let price = td[5].textContent.replace(/\D/gu, '');
                    if (names[j].textContent === td[0].textContent) {
                        buildings.building.push(
                            {
                                'id': td[0].textContent,
                                'name': td[0].textContent,
                                'flats': {
                                    'flat': {
                                        'number': number,
                                        'rooms': rooms,
                                        'price': price,
                                        'area': area,
                                        'img': 'https://magistrat-don.ru' + img,
                                    }
                                },
                            }
                        )
                        break
                    }
                }
            }
        }
        return buildings;
    });

    complex.id = 'crypto.MD5(building.name).toString()';
    complex.name = 'building.name';

    complexes.complexes.complex.push(complex);

    let options = {compact: true, ignoreComment: true, spaces: 4};
    let result = convert.js2xml(complexes, options);

    fs.writeFileSync(
        __dirname + '/../public/storage/xml/_magistratdon:' +
        trans().transform(complex.name, '-').toLowerCase() + '.xml',
        '<?xml version="1.0" encoding="UTF-8" ?>\n' + result
    );

    console.log('done');
}
