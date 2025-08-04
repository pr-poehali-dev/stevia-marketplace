import React, { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import Icon from '@/components/ui/icon';

const Index = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedGame, setSelectedGame] = useState('minecraft');
  const [isLoginOpen, setIsLoginOpen] = useState(false);

  // Пустые товары для демонстрации
  const games = [
    {
      id: 'minecraft',
      name: 'Minecraft',
      icon: '/img/4dcc32db-5185-4e31-84bd-abc2b7d236eb.jpg',
      description: 'Самая популярная песочница',
      itemsCount: 0,
      categories: ['Аккаунты', 'Донат', 'Игровая валюта', 'Предметы']
    }
  ];

  const reviews = [
    {
      id: 1,
      user: 'GamerPro2024',
      rating: 5,
      text: 'Отличная площадка! Быстрые транзакции.',
      date: '2 дня назад',
      avatar: 'GP'
    },
    {
      id: 2,
      user: 'MinecraftFan',
      rating: 5,
      text: 'Надёжно и безопасно. Рекомендую!',
      date: '1 неделю назад',
      avatar: 'MF'
    }
  ];

  const supportAccount = {
    login: 'Вэил',
    password: 'frooz10',
    status: 'online'
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-emerald-50 via-green-50 to-emerald-100">
      {/* Хедер */}
      <header className="bg-white shadow-sm border-b border-green-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center space-x-4">
              <div className="flex items-center space-x-2">
                <div className="w-8 h-8 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center">
                  <Icon name="Gamepad2" className="w-5 h-5 text-white" />
                </div>
                <h1 className="text-2xl font-bold text-emerald-700">Stevia</h1>
              </div>
              <Badge variant="secondary" className="bg-emerald-100 text-emerald-700">
                Игровая площадка
              </Badge>
            </div>
            
            <nav className="hidden md:flex items-center space-x-6">
              <a href="#games" className="text-gray-600 hover:text-emerald-600 transition-colors">Игры</a>
              <a href="#support" className="text-gray-600 hover:text-emerald-600 transition-colors">Поддержка</a>
              <a href="#reviews" className="text-gray-600 hover:text-emerald-600 transition-colors">Отзывы</a>
              
              <Sheet>
                <SheetTrigger asChild>
                  <Button variant="outline" className="border-emerald-500 text-emerald-600 hover:bg-emerald-50">
                    <Icon name="User" className="w-4 h-4 mr-2" />
                    Вход
                  </Button>
                </SheetTrigger>
                <SheetContent>
                  <SheetHeader>
                    <SheetTitle className="text-emerald-700">Вход в аккаунт</SheetTitle>
                    <SheetDescription>
                      Войдите в свой аккаунт для доступа к покупкам
                    </SheetDescription>
                  </SheetHeader>
                  <div className="mt-6 space-y-4">
                    <div>
                      <label className="text-sm font-medium text-gray-700">Логин</label>
                      <Input placeholder="Введите логин" className="mt-1" />
                    </div>
                    <div>
                      <label className="text-sm font-medium text-gray-700">Пароль</label>
                      <Input type="password" placeholder="Введите пароль" className="mt-1" />
                    </div>
                    <Button className="w-full btn-primary">
                      Войти
                    </Button>
                    <div className="text-center">
                      <a href="#" className="text-sm text-emerald-600 hover:underline">
                        Регистрация
                      </a>
                    </div>
                  </div>
                </SheetContent>
              </Sheet>
            </nav>
          </div>
        </div>
      </header>

      {/* Герой секция */}
      <section className="py-20 text-center">
        <div className="max-w-4xl mx-auto px-4">
          <h2 className="text-5xl font-bold text-emerald-800 mb-6">
            Торговая площадка игровых товаров
          </h2>
          <p className="text-xl text-emerald-600 mb-8 max-w-2xl mx-auto">
            Покупайте и продавайте игровые аккаунты, валюту и предметы безопасно и быстро
          </p>
          <div className="flex flex-wrap justify-center gap-4 mb-12">
            <Badge className="bg-emerald-500 text-white px-4 py-2 text-sm">
              <Icon name="Shield" className="w-4 h-4 mr-2" />
              100% безопасно
            </Badge>
            <Badge className="bg-green-500 text-white px-4 py-2 text-sm">
              <Icon name="Clock" className="w-4 h-4 mr-2" />
              24/7 поддержка
            </Badge>
            <Badge className="bg-emerald-600 text-white px-4 py-2 text-sm">
              <Icon name="Users" className="w-4 h-4 mr-2" />
              Реальные продавцы
            </Badge>
          </div>
          
          {/* Поиск */}
          <div className="max-w-md mx-auto relative">
            <Icon name="Search" className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
            <Input
              placeholder="Поиск игр и товаров..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10 py-3 text-lg border-2 border-emerald-200 focus:border-emerald-500"
            />
          </div>
        </div>
      </section>

      {/* Секция игр */}
      <section id="games" className="py-16">
        <div className="max-w-7xl mx-auto px-4">
          <h3 className="text-3xl font-bold text-emerald-800 mb-8 text-center">Популярные игры</h3>
          
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {games.map((game) => (
              <Card key={game.id} className="game-card card-hover cursor-pointer" onClick={() => setSelectedGame(game.id)}>
                <CardHeader className="text-center">
                  <div className="w-20 h-20 mx-auto mb-4 rounded-2xl overflow-hidden shadow-lg">
                    <img 
                      src={game.icon} 
                      alt={game.name} 
                      className="w-full h-full object-cover"
                    />
                  </div>
                  <CardTitle className="text-2xl text-emerald-700">{game.name}</CardTitle>
                  <CardDescription className="text-emerald-600">{game.description}</CardDescription>
                </CardHeader>
                <CardContent>
                  <div className="flex justify-between items-center mb-4">
                    <Badge variant="secondary" className="bg-emerald-100 text-emerald-700">
                      {game.itemsCount} товаров
                    </Badge>
                    <Icon name="ChevronRight" className="w-5 h-5 text-emerald-500" />
                  </div>
                  
                  <div className="space-y-2">
                    {game.categories.map((category, index) => (
                      <div key={index} className="flex justify-between items-center p-2 rounded bg-emerald-50 hover:bg-emerald-100 transition-colors">
                        <span className="text-emerald-700 font-medium">{category}</span>
                        <Badge variant="outline" className="text-emerald-600 border-emerald-300">
                          0 шт.
                        </Badge>
                      </div>
                    ))}
                  </div>
                  
                  <div className="mt-6 text-center">
                    <p className="text-gray-500 text-sm mb-3">Товары скоро появятся!</p>
                    <Button className="btn-secondary w-full">
                      <Icon name="Plus" className="w-4 h-4 mr-2" />
                      Выставить товар
                    </Button>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Админ панель */}
      <section className="py-16 bg-white">
        <div className="max-w-4xl mx-auto px-4">
          <h3 className="text-3xl font-bold text-emerald-800 mb-8 text-center">Админ панель</h3>
          
          <Tabs defaultValue="promo" className="w-full">
            <TabsList className="grid w-full grid-cols-3">
              <TabsTrigger value="promo">Промокоды</TabsTrigger>
              <TabsTrigger value="users">Пользователи</TabsTrigger>
              <TabsTrigger value="stats">Статистика</TabsTrigger>
            </TabsList>
            
            <TabsContent value="promo" className="space-y-4">
              <Card>
                <CardHeader>
                  <CardTitle className="text-emerald-700">Создание промокода</CardTitle>
                  <CardDescription>Добавьте новый промокод с активацией и суммой</CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="text-sm font-medium text-gray-700">Код</label>
                      <Input placeholder="WELCOME2024" />
                    </div>
                    <div>
                      <label className="text-sm font-medium text-gray-700">Сумма скидки</label>
                      <Input placeholder="100" type="number" />
                    </div>
                  </div>
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="text-sm font-medium text-gray-700">Активаций</label>
                      <Input placeholder="50" type="number" />
                    </div>
                    <div>
                      <label className="text-sm font-medium text-gray-700">Активность до</label>
                      <Input type="date" />
                    </div>
                  </div>
                  <Button className="btn-primary">
                    <Icon name="Plus" className="w-4 h-4 mr-2" />
                    Создать промокод
                  </Button>
                </CardContent>
              </Card>
              
              <div className="grid gap-4">
                <div className="bg-emerald-50 p-4 rounded-lg border border-emerald-200">
                  <div className="flex justify-between items-center">
                    <div>
                      <h4 className="font-medium text-emerald-700">NEWUSER50</h4>
                      <p className="text-sm text-emerald-600">Скидка 50₽ • Активно до 31.12.2024</p>
                    </div>
                    <Badge className="bg-emerald-500 text-white">25/100 использований</Badge>
                  </div>
                </div>
              </div>
            </TabsContent>
            
            <TabsContent value="users">
              <Card>
                <CardHeader>
                  <CardTitle className="text-emerald-700">Активные пользователи</CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="text-center py-8">
                    <Icon name="Users" className="w-16 h-16 text-emerald-300 mx-auto mb-4" />
                    <p className="text-gray-500 text-lg">Пользователи появятся после запуска</p>
                  </div>
                </CardContent>
              </Card>
            </TabsContent>
            
            <TabsContent value="stats">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <Card>
                  <CardContent className="p-6 text-center">
                    <Icon name="ShoppingCart" className="w-8 h-8 text-emerald-500 mx-auto mb-2" />
                    <div className="text-2xl font-bold text-emerald-700">0</div>
                    <div className="text-sm text-gray-600">Продаж</div>
                  </CardContent>
                </Card>
                <Card>
                  <CardContent className="p-6 text-center">
                    <Icon name="DollarSign" className="w-8 h-8 text-emerald-500 mx-auto mb-2" />
                    <div className="text-2xl font-bold text-emerald-700">0₽</div>
                    <div className="text-sm text-gray-600">Оборот</div>
                  </CardContent>
                </Card>
                <Card>
                  <CardContent className="p-6 text-center">
                    <Icon name="Users" className="w-8 h-8 text-emerald-500 mx-auto mb-2" />
                    <div className="text-2xl font-bold text-emerald-700">0</div>
                    <div className="text-sm text-gray-600">Пользователей</div>
                  </CardContent>
                </Card>
              </div>
            </TabsContent>
          </Tabs>
        </div>
      </section>

      {/* Поддержка */}
      <section id="support" className="py-16 bg-emerald-50">
        <div className="max-w-4xl mx-auto px-4">
          <h3 className="text-3xl font-bold text-emerald-800 mb-8 text-center">Поддержка</h3>
          
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            <Card>
              <CardHeader>
                <CardTitle className="text-emerald-700 flex items-center">
                  <Icon name="MessageCircle" className="w-5 h-5 mr-2" />
                  Аккаунт поддержки
                </CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-3">
                  <div className="flex items-center space-x-3">
                    <div className="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span className="text-green-600 font-medium">Онлайн</span>
                  </div>
                  <div>
                    <div className="text-sm text-gray-600">Логин:</div>
                    <div className="font-mono text-emerald-700 bg-emerald-100 px-2 py-1 rounded">
                      {supportAccount.login}
                    </div>
                  </div>
                  <div>
                    <div className="text-sm text-gray-600">Пароль:</div>
                    <div className="font-mono text-emerald-700 bg-emerald-100 px-2 py-1 rounded">
                      {supportAccount.password}
                    </div>
                  </div>
                  <Button className="btn-primary w-full mt-4">
                    <Icon name="MessageSquare" className="w-4 h-4 mr-2" />
                    Написать в поддержку
                  </Button>
                </div>
              </CardContent>
            </Card>
            
            <Card>
              <CardHeader>
                <CardTitle className="text-emerald-700">Задать вопрос</CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <div>
                  <label className="text-sm font-medium text-gray-700">Тема</label>
                  <Input placeholder="Опишите проблему кратко" />
                </div>
                <div>
                  <label className="text-sm font-medium text-gray-700">Сообщение</label>
                  <Textarea placeholder="Подробно опишите вашу проблему..." rows={4} />
                </div>
                <Button className="btn-primary w-full">
                  <Icon name="Send" className="w-4 h-4 mr-2" />
                  Отправить
                </Button>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>

      {/* Отзывы */}
      <section id="reviews" className="py-16">
        <div className="max-w-6xl mx-auto px-4">
          <h3 className="text-3xl font-bold text-emerald-800 mb-8 text-center">Отзывы пользователей</h3>
          
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {reviews.map((review) => (
              <Card key={review.id} className="border-emerald-100">
                <CardContent className="p-6">
                  <div className="flex items-start space-x-4">
                    <Avatar>
                      <AvatarFallback className="bg-emerald-500 text-white">
                        {review.avatar}
                      </AvatarFallback>
                    </Avatar>
                    <div className="flex-1">
                      <div className="flex items-center justify-between mb-2">
                        <h4 className="font-medium text-emerald-700">{review.user}</h4>
                        <div className="flex space-x-1">
                          {[...Array(review.rating)].map((_, i) => (
                            <Icon key={i} name="Star" className="w-4 h-4 fill-yellow-400 text-yellow-400" />
                          ))}
                        </div>
                      </div>
                      <p className="text-gray-600 mb-2">{review.text}</p>
                      <p className="text-sm text-gray-400">{review.date}</p>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
          
          <Card className="max-w-2xl mx-auto">
            <CardHeader>
              <CardTitle className="text-emerald-700 text-center">Оставить отзыв</CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="flex justify-center space-x-2">
                {[...Array(5)].map((_, i) => (
                  <Icon key={i} name="Star" className="w-6 h-6 text-gray-300 hover:text-yellow-400 cursor-pointer" />
                ))}
              </div>
              <Textarea placeholder="Расскажите о своём опыте использования площадки..." rows={4} />
              <Button className="btn-primary w-full">
                <Icon name="MessageSquare" className="w-4 h-4 mr-2" />
                Опубликовать отзыв
              </Button>
            </CardContent>
          </Card>
        </div>
      </section>

      {/* Футер */}
      <footer className="bg-emerald-800 text-white py-12">
        <div className="max-w-7xl mx-auto px-4">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
              <div className="flex items-center space-x-2 mb-4">
                <div className="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                  <Icon name="Gamepad2" className="w-5 h-5 text-emerald-600" />
                </div>
                <h4 className="text-xl font-bold">Stevia</h4>
              </div>
              <p className="text-emerald-200">
                Безопасная торговая площадка игровых товаров
              </p>
            </div>
            
            <div>
              <h5 className="font-semibold mb-4">Игры</h5>
              <ul className="space-y-2 text-emerald-200">
                <li><a href="#" className="hover:text-white transition-colors">Minecraft</a></li>
                <li><a href="#" className="hover:text-white transition-colors">Скоро...</a></li>
              </ul>
            </div>
            
            <div>
              <h5 className="font-semibold mb-4">Поддержка</h5>
              <ul className="space-y-2 text-emerald-200">
                <li><a href="#" className="hover:text-white transition-colors">Связаться с нами</a></li>
                <li><a href="#" className="hover:text-white transition-colors">FAQ</a></li>
                <li><a href="#" className="hover:text-white transition-colors">Правила</a></li>
              </ul>
            </div>
            
            <div>
              <h5 className="font-semibold mb-4">Безопасность</h5>
              <ul className="space-y-2 text-emerald-200">
                <li><a href="#" className="hover:text-white transition-colors">Политика конфиденциальности</a></li>
                <li><a href="#" className="hover:text-white transition-colors">Условия использования</a></li>
              </ul>
            </div>
          </div>
          
          <div className="border-t border-emerald-700 mt-8 pt-8 text-center text-emerald-200">
            <p>&copy; 2024 Stevia. Все права защищены.</p>
            <p className="mt-2 text-sm">0 товаров • Ждём первых продавцов!</p>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default Index;