import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { IconSchool } from '@tabler/icons-react';

export default function ApplicationLogo({ bgLogo, colorLogo, colorText, href = '#' }) {
    return (
        <Link href={href} className={cn('flex flex-row items-center gap-x-2')}>
            <div
                className={cn(
                    'flex aspect-square size-12 items-center justify-center rounded-full bg-gradient-to-r text-foreground',
                    bgLogo,
                )}
            >
                <IconSchool className={cn('size-8', colorLogo)} />
            </div>
            <div className={cn('grid flex-1 text-left leading-tight', colorText)}>
                <span className="truncate font-bold">SIAKADMU</span>
                <span className="truncate text-xs tracking-tighter">Sistem Informasi Akademik SMP Muhammadiyah</span>
            </div>
        </Link>
    );
}
