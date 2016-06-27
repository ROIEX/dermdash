var QBApp = {
  appId: 42379,
  authKey: 'R8Ey7R6kWFpZ-rs',
  authSecret: 'a4bz3de9T2ryZVk'
};

var config = {
  chatProtocol: {
    active: 2
  },
  debug: {
    mode: 1,
    file: null
  },
  stickerpipe: {
    elId: 'stickers_btn',

    apiKey: '847b82c49db21ecec88c510e377b452c',

    enableEmojiTab: false,
    enableHistoryTab: true,
    enableStoreTab: true,

    userId: null,

    priceB: '0.99 $',
    priceC: '1.99 $'
  }
};

var QBUser1 = {
        id: 6729114,
        name: 'dermdash admin',
        login: 'dermdash_admin_user',
        pass: '11111111'
    };

QB.init(QBApp.appId, QBApp.authKey, QBApp.authSecret, config);
