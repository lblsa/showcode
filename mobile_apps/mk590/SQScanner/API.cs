//--------------------------------------------------------------------
// FILENAME: API.cs
// Copyright © 2011 Motorola Solutions, Inc. All rights reserved.
// -------------------------------------------------------------------
// DESCRIPTION: Реализует код, взаимодействующий с API сканнера 
// Symbol.Barcode библиотеки EMDK для .NET.
//--------------------------------------------------------------------
using System;
using System.Windows.Forms;

namespace SQScanner
{
	/// <summary>
    /// Класс, взаимодействующий с API сканнера Symbol.Barcode библиотеки EMDK для .NET 
	/// </summary>
	class API
	{
		private Symbol.Barcode.Reader myReader = null;
		private Symbol.Barcode.ReaderData myReaderData = null;
		private System.EventHandler myReadNotifyHandler = null;
		private System.EventHandler myStatusNotifyHandler =  null;

		/// <summary>
		/// Метод инициализации Reader'a.
		/// </summary>
		public bool InitReader()
		{
			// Если Reader уже инициализирован, прекращаем инициализацию.
			if (myReader != null)
			{
				return false;
			}
			else // Иначе инициализируем Reader.
			{
				try
				{
					// Получаем устройство пользователя.
					Symbol.Generic.Device MyDevice =
						Symbol.StandardForms.SelectDevice.Select(
						Symbol.Barcode.Device.Title,
						Symbol.Barcode.Device.AvailableDevices);

					if (MyDevice == null)
					{
						MessageBox.Show("Устройство не задано!", "Выберите устройство!");
						return false;
					}

					// Создаем объект Reader'a для заданного устройства.
					myReader = new Symbol.Barcode.Reader(MyDevice);

					// Создаем объект данных Reader'a.
					myReaderData = new Symbol.Barcode.ReaderData(
						Symbol.Barcode.ReaderDataTypes.Text,
						Symbol.Barcode.ReaderDataLengths.MaximumLabel);

					// Включаем Reader.
					myReader.Actions.Enable();

                    // In this sample, we are setting the aim type to trigger. 
                    switch (myReader.ReaderParameters.ReaderType)
                    {
                        case Symbol.Barcode.READER_TYPE.READER_TYPE_IMAGER:
                            myReader.ReaderParameters.ReaderSpecific.ImagerSpecific.AimType = Symbol.Barcode.AIM_TYPE.AIM_TYPE_TRIGGER;
                            break;
                        case Symbol.Barcode.READER_TYPE.READER_TYPE_LASER:
                            myReader.ReaderParameters.ReaderSpecific.LaserSpecific.AimType = Symbol.Barcode.AIM_TYPE.AIM_TYPE_TRIGGER;
                            break;
                        case Symbol.Barcode.READER_TYPE.READER_TYPE_CONTACT:
                            // AimType is not supported by the contact readers.
                            break;
                    }
                    myReader.Actions.SetParameters();
				}

                // Далее ловим исключительные ситуации
				catch (Symbol.Exceptions.OperationFailureException ex)
				{
					MessageBox.Show(Resources.GetString("InitReader")+"\n" +
						Resources.GetString("OperationFailure") + "\n" + ex.Message +
						"\n" +
						Resources.GetString("Result") +" = " + (Symbol.Results)((uint)ex.Result)
						);

					return false;
				}
				catch (Symbol.Exceptions.InvalidRequestException ex)
				{
                    MessageBox.Show(Resources.GetString("InitReader") + "\n" +
						Resources.GetString("InvalidRequest") + "\n" +
						ex.Message);

					return false;
				}
				catch (Symbol.Exceptions.InvalidIndexerException ex)
				{
                    MessageBox.Show(Resources.GetString("InitReader") + "\n" +
                        Resources.GetString("InvalidIndexer") + "\n" +
						ex.Message);

					return false;
				};

				return true;
			}
		}

		/// <summary>
		/// Прекращаем считываение и удаляем объект Reader'a.
		/// </summary>
		public void TermReader()
		{
			// Если объект Reader'a создан...
			if (myReader != null)
			{
				try
				{
					// останавливаем отправку всех уведомлений.
					StopRead();

                    // Отсоединяем все обработчики уведомлений, если пользователь еще этого не сделал.
					DetachReadNotify();
					DetachStatusNotify();

					// Отключаем Reader.
					myReader.Actions.Disable();

					// Освобождаем объект Reader'a.
					myReader.Dispose();

					// Обнуляем ссылку на объект Reader'a.
					myReader = null;
				}

                // Ловим исключительные ситуации при освобождении объекта Reader'a.
				catch (Symbol.Exceptions.OperationFailureException ex)
				{
					MessageBox.Show(Resources.GetString("TermReader") + "\n" +
                        Resources.GetString("OperationFailure") + "\n" + ex.Message +
						"\n" +
                        Resources.GetString("Result") + " = " + (Symbol.Results)((uint)ex.Result)
						);
				}
				catch (Symbol.Exceptions.InvalidRequestException ex)
				{
                    MessageBox.Show(Resources.GetString("TermReader") + "\n" +
                        Resources.GetString("InvalidRequest") + "\n" +
						ex.Message);
				}
				catch (Symbol.Exceptions.InvalidIndexerException ex)
				{
                    MessageBox.Show(Resources.GetString("TermReader") + "\n" +
                        Resources.GetString("InvalidIndexer") + "\n" +
						ex.Message);
				};
			}

			// После освобождения объекта Reader'a, освобождаем объект данных Reader'a. 
			if (myReaderData != null)
			{
				try
				{
					// Освобождаем объект данных.
					myReaderData.Dispose();

					// Обнуляем ссылку на объект данных.
					myReaderData = null;
				}

                // Ловим исключительные ситуации при освобождении объекта данных Reader'a.
				catch (Symbol.Exceptions.OperationFailureException ex)
				{
                    MessageBox.Show(Resources.GetString("TermReader") + "\n" +
                        Resources.GetString("OperationFailure") + "\n" + ex.Message +
						"\n" +
                        Resources.GetString("Result") + " = " + (Symbol.Results)((uint)ex.Result)
						);
				}
				catch (Symbol.Exceptions.InvalidRequestException ex)
				{
                    MessageBox.Show(Resources.GetString("TermReader") + "\n" +
                        Resources.GetString("InvalidRequest") + "\n" +
						ex.Message);
				}
				catch (Symbol.Exceptions.InvalidIndexerException ex)
				{
                    MessageBox.Show(Resources.GetString("TermReader") + "\n" +
                        Resources.GetString("InvalidIndexer") + "\n" +
						ex.Message);
				};
			}
		}

		/// <summary>
		/// Метод считывает билеты Reader'ом.
		/// </summary>
		public void StartRead(bool toggleSoftTrigger)
		{
			// Проверяем созданы ли у нас объекты Reader'a и данных Reader'a.
			if ((myReader != null) &&
				(myReaderData != null))

				try
				{
					if ( !myReaderData.IsPending )
					{
                        // Submit a read.
                        myReader.Actions.Read(myReaderData);

                        if (toggleSoftTrigger && myReader.Info.SoftTrigger == false)
                        {
                            myReader.Info.SoftTrigger = true;
                        }
					}
				}

                // Ловим исключительные ситуации при считывании данных Reader'ом.
				catch (Symbol.Exceptions.OperationFailureException ex)
				{
					MessageBox.Show(Resources.GetString("StartRead") + "\n" +
                        Resources.GetString("OperationFailure") + "\n" + ex.Message +
						"\n" +
                        Resources.GetString("Result") + " = " + (Symbol.Results)((uint)ex.Result));
				}
				catch (Symbol.Exceptions.InvalidRequestException ex)
				{
                    MessageBox.Show(Resources.GetString("StartRead") + "\n" +
                        Resources.GetString("InvalidRequest") + "\n" +
						ex.Message);
				}
				catch (Symbol.Exceptions.InvalidIndexerException ex)
				{
                    MessageBox.Show(Resources.GetString("StartRead") + "\n" +
                        Resources.GetString("InvalidIndexer") + "\n" +
						ex.Message);
				};
		}

		/// <summary>
		/// Метод прекращает считывание билетов Reader'ом.
		/// </summary>
		public void StopRead()
		{
			//If we have a reader
			if (myReader != null)
			{
				try
				{
					// Flush (Cancel all pending reads).
                    if (myReader.Info.SoftTrigger == true)
                    {
                        myReader.Info.SoftTrigger = false;
                    }
                    myReader.Actions.Flush();
				}

				catch (Symbol.Exceptions.OperationFailureException ex)
				{
					MessageBox.Show(Resources.GetString("StopRead") + "\n" +
                        Resources.GetString("OperationFailure") + "\n" + ex.Message +
						"\n" +
                        Resources.GetString("Result") + " = " + (Symbol.Results)((uint)ex.Result)
						);
				}
				catch (Symbol.Exceptions.InvalidRequestException ex)
				{
                    MessageBox.Show(Resources.GetString("StopRead") + "\n" +
                        Resources.GetString("InvalidRequest") + "\n" +
						ex.Message);
				}
				catch (Symbol.Exceptions.InvalidIndexerException ex)
				{
                    MessageBox.Show(Resources.GetString("StopRead") + "\n" +
						Resources.GetString("InvalidIndexer") + "\n" +
						ex.Message);
				};
			}
		}

		/// <summary>
		/// Provides the access to the Symbol.Barcode.Reader reference.
		/// The user can use this reference for his additional Reader - related operations.
		/// </summary>
		public Symbol.Barcode.Reader Reader 
		{
			get
			{
				return myReader;
			}
		}

		/// <summary>
		/// Метод присоединяет обработчик уведомлений о статусе чтения.
		/// </summary>
		public void AttachReadNotify(System.EventHandler ReadNotifyHandler)
		{
			// If we have a reader
			if( myReader != null)
			{
				// Attach the read notification handler.
				myReader.ReadNotify += ReadNotifyHandler;
				myReadNotifyHandler = ReadNotifyHandler;
			}

		}

		/// <summary>
		/// Метод отсоединяет обработчик уведомлений о статусе чтения.
		/// </summary>
		public void DetachReadNotify()
		{
			if(( myReader != null)&&(myReadNotifyHandler!=null))
			{
				// Detach the read notification handler.
				myReader.ReadNotify -= myReadNotifyHandler;
                myReadNotifyHandler = null;
			}
		}

		/// <summary>
		/// Метод присоединяет обработчик уведомлений о статусе приложения.
		/// </summary>
		public void AttachStatusNotify(System.EventHandler StatusNotifyHandler)
		{
			// If we have a reader
			if( myReader != null)
			{
				// Attach status notification handler.
				myReader.StatusNotify += StatusNotifyHandler;
				myStatusNotifyHandler = StatusNotifyHandler;
			}
		}

		/// <summary>
		/// Метод отсоединяет обработчик уведомлений о статусе приложения.
		/// </summary>
		public void DetachStatusNotify()
		{
			// If we have a reader registered for receiving the status notifications
			if((myReader != null)&&(myStatusNotifyHandler!=null))
			{
				// Detach the status notification handler.
				myReader.StatusNotify -= myStatusNotifyHandler;
                myStatusNotifyHandler = null;
			}
		}
	}
}