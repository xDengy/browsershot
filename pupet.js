const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch({
        args: [
            '--disable-web-security',
            '--disable-features=IsolateOrigins,site-per-process',
        ],
    });

    const page = await browser.newPage();

    await page.goto('https://bitrix24.alfastroyinvest.com/extranet/bizprofi.realty/', {timeout: 50000});

    await page.type('input[name="USER_LOGIN"]', 'alfaagenstvo@gmail.com');
    await page.type('input[name="USER_PASSWORD"]', '13572468');
    await page.click('input[type="submit"]');

    console.log('enter');

    await page.waitForSelector('iframe.app-frame');

    const elementHandle = await page.$('iframe.app-frame');
    const frame = await elementHandle.contentFrame();

    await frame.waitForSelector('.grid-tile');

    const res = await frame.evaluate(() => {
        return document.querySelector('body').innerText;
    });

    console.log(res);
})();
