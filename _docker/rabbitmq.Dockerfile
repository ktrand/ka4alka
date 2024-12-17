FROM rabbitmq:4.0.4-management

ADD https://github.com/rabbitmq/rabbitmq-delayed-message-exchange/releases/download/v4.0.2/rabbitmq_delayed_message_exchange-4.0.2.ez /plugins/

#RUN rabbitmq-plugins enable rabbitmq_delayed_message_exchange

WORKDIR /plugins/
