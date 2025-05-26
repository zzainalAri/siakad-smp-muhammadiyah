import { Slot } from '@radix-ui/react-slot';
import { cva } from 'class-variance-authority';
import * as React from 'react';

import { cn } from '@/lib/utils';

const buttonVariants = cva(
    'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0',
    {
        variants: {
            variant: {
                default: 'bg-primary text-primary-foreground shadow hover:bg-primary/90',
                destructive: 'bg-destructive text-destructive-foreground shadow-sm hover:bg-destructive/90',
                outline: 'border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground',
                secondary: 'bg-secondary text-secondary-foreground shadow-sm hover:bg-secondary/80',
                ghost: 'hover:bg-accent hover:text-accent-foreground',
                link: 'text-primary underline-offset-4 hover:underline',
                red: 'text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700',
                slate: 'text-white bg-gradient-to-r from-slate-500 via-slate-600 to-slate-700',
                orange: 'text-white bg-gradient-to-r from-orange-500 via-orange-600 to-orange-700',
                blue: 'text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700',
                green: 'text-white bg-gradient-to-r from-green-500 via-green-600 to-green-700',
                yellow: 'text-white bg-gradient-to-r from-yellow-500 via-yellow-600 to-yellow-700',
                purple: 'text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700',
            },
            size: {
                default: 'h-9 px-4 py-2 rounded-lg',
                xs: 'h-6 rounded-md px-3 text-xs',
                sm: 'h-8 rounded-md px-3 text-xs',
                lg: 'h-10 rounded-xl px-8',
                xl: 'h-12 rounded-xl px-8',
                icon: 'h-9 w-9',
            },
        },
        defaultVariants: {
            variant: 'default',
            size: 'default',
        },
    },
);

const Button = React.forwardRef(({ className, variant, size, asChild = false, ...props }, ref) => {
    const Comp = asChild ? Slot : 'button';
    return <Comp className={cn(buttonVariants({ variant, size, className }))} ref={ref} {...props} />;
});
Button.displayName = 'Button';

export { Button, buttonVariants };
