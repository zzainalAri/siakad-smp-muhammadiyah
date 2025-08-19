import CalendarSchedule from '@/Components/CalendarSchedule';
import HeaderTitle from '@/Components/HeaderTitle';
import { Card, CardContent } from '@/Components/ui/card';
import AppLayout from '@/Layouts/AppLayout';
import { groupSchedulesByDay } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { IconCalendar } from '@tabler/icons-react';

export default function Index(props) {
    const schedules = props.scheduleTable;
    const days = props.days;

    const mobile_schedules = groupSchedulesByDay(props.mobile_schedules);

    return (
        <>
            <div className="flex w-full flex-col pb-32">
                <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                    <HeaderTitle
                        title={props.page_setting.title}
                        subtitle={props.page_setting.subtitle}
                        icon={IconCalendar}
                    />
                </div>
                <Card>
                    <CardContent className="p-4">
                        <CalendarSchedule days={days} schedules={schedules} />
                        <div className="flex lg:hidden">
                            <div className="w-full space-y-8">
                                {days.map((day) => (
                                    <Link href={'#'} key={day}>
                                        <h3 className="my-2 text-lg font-semibold">{day}</h3>
                                        <ul className="space-y-2">
                                            {(mobile_schedules[day] || []).map((schedule, index) => (
                                                <li key={index} className="rounded-md border p-3 shadow">
                                                    <Link href={route('teachers.courses.show', [schedule.course_code])}>
                                                        <p className="font-medium">{schedule.course}</p>
                                                        <p className="text-sm font-semibold">{schedule.classroom}</p>
                                                        <p className="text-sm text-muted-foreground">
                                                            {schedule.start_time} - {schedule.end_time}
                                                        </p>
                                                    </Link>
                                                </li>
                                            ))}
                                        </ul>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

Index.layout = (page) => <AppLayout children={page} title={page.props.page_setting.title} />;
