import { describe, it, expect } from 'vitest';
import PsUtils from '../../Modules/TemplateCore/utils/PsUtils';

describe('PsUtils', () => {
    describe('formatPrice', () => {
        const appInfoStore = {
            appInfo: {
                data: {
                    mobileSetting: {
                        price_format: '##,###'
                    }
                }
            }
        };

        it('should format the price without decimal and separate comma every three digits.', () => {
            appInfoStore.appInfo.data.mobileSetting.price_format =  '##,###';
            const price = 1234.56;
            const formattedPrice = PsUtils.formatPrice(appInfoStore, price);
            expect(formattedPrice).toBe("1,234");
        });

        it('should format the price with two decimal places and separate comma every three digits.', () => {
            appInfoStore.appInfo.data.mobileSetting.price_format = '##,###.00';
            const price = 1234567890.12;
            const formattedPrice = PsUtils.formatPrice(appInfoStore, price);
            expect(formattedPrice).toBe("1,234,567,890.12");
        });

        it('should format the price with two decimal places and separate comma every four digits.', () => {
            appInfoStore.appInfo.data.mobileSetting.price_format = '#,####.00';
            const price = 1234567890.12;
            const formattedPrice = PsUtils.formatPrice(appInfoStore, price);
            expect(formattedPrice).toBe("12,3456,7890.12");
        });

        it('should format the price without decimal and separate comma every two digits.', () => {
            appInfoStore.appInfo.data.mobileSetting.price_format = '##,##0';
            const price = 1234567890.60;
            const formattedPrice = PsUtils.formatPrice(appInfoStore, price);
            expect(formattedPrice).toBe("1,234,567,890");
        });

        it('should format the price with two decimal places and separate comma every four digits.', () => {
            appInfoStore.appInfo.data.mobileSetting.price_format = '#,####.00';
            const price = 1234567890.12;
            const formattedPrice = PsUtils.formatPrice(appInfoStore, price);
            expect(formattedPrice).toBe("12,3456,7890.12");
        });

        it('should format the price with two decimal places and separate dot every three digits.', () => {
            appInfoStore.appInfo.data.mobileSetting.price_format = '#.###,00';
            const price = 1234567890.12;
            const formattedPrice = PsUtils.formatPrice(appInfoStore, price);
            expect(formattedPrice).toBe("1.234.567.890,12");
        });

        it('should format the price without decimal and no separator.', () => {
            appInfoStore.appInfo.data.mobileSetting.price_format = '###';
            const price = 1234567890.77;
            const formattedPrice = PsUtils.formatPrice(appInfoStore, price);
            expect(formattedPrice).toBe("1234567890");
        });

        it('should format the price with one decimal place and no separator.', () => {
            appInfoStore.appInfo.data.mobileSetting.price_format = '##0.0';
            const price = 1234.56;
            const formattedPrice = PsUtils.formatPrice(appInfoStore, price);
            expect(formattedPrice).toBe("1234.6");
        });

    });
});
