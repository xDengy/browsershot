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

    // тут обработка
    let text = await page.evaluate(() => {
        return document.querySelector('.body-big').textContent;
    });

    console.log(text);

    // let options = {compact: true, ignoreComment: true, spaces: 4};
    // let result = convert.js2xml(complexes, options);
    //
    // fs.writeFileSync(
    //     __dirname + '/../public/storage/xml/_magistratdon:' +
    //     trans().transform(complex.name, '-').toLowerCase() + '.xml',
    //     '<?xml version="1.0" encoding="UTF-8" ?>\n' + result
    // );
}
