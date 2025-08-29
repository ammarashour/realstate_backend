class PsLazyLoader {
    public static load<T = Promise<any>>(
        timeout = 100,
        loadImport: () => Promise<{ default: T }>
    ): Promise<{ default: T }> {
        return new Promise((resolve) => {
            setTimeout(() => {
                if (loadImport != null) {
                    resolve(loadImport());
                }
            }, timeout);
        });
    }
}

export default PsLazyLoader;
