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

    await page.goto('https://bitrix24.alfastroyinvest.com/extranet/bizprofi.realty/');

    await page.type('input[name="USER_LOGIN"]', 'alfaagenstvo@gmail.com');
    await page.type('input[name="USER_PASSWORD"]', '13572468');
    await page.click('input[type="submit"]');

    console.log('Вход по логину');

    await page.waitForSelector('iframe.app-frame');

    const elementHandle = await page.$('iframe.app-frame');
    const frame = await elementHandle.contentFrame();

    console.log('Выбрал фрейм');

    // Выбрать объекты
    await frame.waitForSelector('.main-buttons-item:nth-child(2)');
    await frame.click('.main-buttons-item:nth-child(2)');

    console.log('Выбрал объекты');

    // Выбрать плитку с ценами
    await frame.waitForSelector('#large');
    await frame.click('#large');

    console.log('Выбрал плитку с ценами');

    await build(frame, 1);
    await build(frame, 2);

    await page.close();
    await browser.close();
    console.log('browser closed');
})();

async function build(frame, number) {
    const complexes = {complexes: {complex: []}};
    const complex = {};

    // Выбрать ЖК
    await frame.click('.main-ui-filter-block:nth-of-type(2) .ui-tile-selector-selector-wrap');
    await frame.click('.popup-window > div > div > div > div:nth-of-type('+number+') [data-entity-type="DEPARTMENTS"]');
    await frame.click('.flex-filter-front .ui-btn-icon-search');

    complex.name = await frame.evaluate(() => {
        return document.querySelector('.main-ui-filter-block:nth-of-type(2) .ui-tile-selector-selector-wrap').textContent;
    });
    complex.id = crypto.MD5(complex.name).toString();

    console.log('Выбрал ЖК', complex.name);

    // Дождаться загрузки плитки
    await frame.waitForTimeout(3000);
    //await frame.waitForSelector('.tiles-house-section-floor-offer-rooms');

    console.log('Плитка загрузилась');

    // await page.screenshot({path: 'test.png'});
    complex.buildings = await frame.evaluate(() => {
        const buildings = {building: []};

        document.querySelectorAll('.tiles-house').forEach((element, index) => {
            const building = {};

            building.name = element.querySelector('h2').textContent;
            building.flats = {flat: []};

            element.querySelectorAll('.tiles-house-section-floor-offer.color-free').forEach((node) => {
                const get = (selector) => node.querySelector(selector).textContent;

                let rooms = get('.tiles-house-section-floor-offer-rooms').replace(/\D/gu, '');
                let apartment = get('.tiles-house-section-floor-offer-apartment-number').replace(/\D/gu, '');
                let area = get('.tiles-house-section-floor-offer-area').replace(/[^0-9\.]+/gu, '');
                let price = get('.tiles-house-section-floor-offer-price').replace(/\D/gu, '');

                building.flats.flat.push({
                    rooms,
                    apartment,
                    area,
                    price,
                });
            });

            buildings.building.push(building);
        });

        return buildings;
    });

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
        trans().transform(complex.name, '-').toLowerCase() + '.xml',
        result
    );

    console.log('finish', complex.name);
}
