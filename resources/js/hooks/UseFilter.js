import { router } from '@inertiajs/react';
import pkg from 'lodash';
import { useCallback, useEffect } from 'react';

export default function UseFilter({ route, values, only, wait = 300 }) {
    const { debounce, pickBy } = pkg;

    const reload = useCallback(
        debounce((query) => {
            router.get(route, pickBy(query), {
                only: only,
                preserveScroll: true,
                preserveState: true,
            });
        }, wait),
        [],
    );

    useEffect(() => reload(values), [values, reload]);

    return {
        values,
    };
}
