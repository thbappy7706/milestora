import { Languages } from 'lucide-react';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

const locales = [
    { code: 'en', label: 'English', available: true },
    { code: 'bn', label: 'বাংলা', available: false },
] as const;

export function LanguageSwitcher() {
    return (
        <SidebarMenu>
            <SidebarMenuItem>
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <SidebarMenuButton size="sm">
                            <Languages />
                            <span>English</span>
                        </SidebarMenuButton>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent
                        side="right"
                        align="end"
                        className="w-44"
                    >
                        <DropdownMenuLabel>Language</DropdownMenuLabel>
                        <DropdownMenuSeparator />

                        {locales.map((locale) => (
                            <DropdownMenuItem
                                key={locale.code}
                                disabled={!locale.available}
                                className="justify-between"
                            >
                                <span>{locale.label}</span>
                                {!locale.available && (
                                    <span className="text-xs text-muted-foreground">
                                        Soon
                                    </span>
                                )}
                            </DropdownMenuItem>
                        ))}
                    </DropdownMenuContent>
                </DropdownMenu>
            </SidebarMenuItem>
        </SidebarMenu>
    );
}
